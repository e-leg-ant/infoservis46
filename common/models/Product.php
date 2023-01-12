<?php

namespace common\models;

use Yii;
use common\models\ProductsCategories;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use common\models\ProductImage;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use yz\shoppingcart\CartPositionInterface;
use yii\helpers\BaseInflector;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $price
 * @property string $preview_text
 * @property string $full_description
 * @property integer $status
 * @property integer $discount_id
 * @property integer $brand_id
 * @property string $code
 * @property string $model
 * @property string $unit
 * @property string $h1
 * @property string $auto
 */
class Product extends \yii\db\ActiveRecord implements CartPositionInterface
{
    public $category;
    public $images;
    public $loadedImages;
    public $deleteImages = [];
    public $noImageProduct = '/storage/product-no-image.png';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @return float
     */
    public function getPrice() {
        return (float) $this->price;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param bool $withDiscount
     * @return integer
     */
    public function getCost($withDiscount = true) {

        $cost = (float)$this->price * (int)$this->quantity;

        if ($withDiscount && !empty($this->discount)) {

            $discount = (int)str_replace('%', '', $this->discount->name);

            return round($cost - ($cost * $discount / 100));

        } else {
            return $cost;
        }
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity() {
        return (int)$this->quantity;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            // Запись имени URL для ЧПУ для нового товара

            // Проверка уникальности записываемого ЧПУ

            $slug = BaseInflector::slug($this->name);

            $model = Product::find()->where(['slug' => $slug]);
            
            // если происходит обновление товара, то не проверяем slug у текущего товара
            
            if (!$insert) {
                $model = $model->andWhere(['not', ['id' => $this->id]]);
            }

            $model = $model->all();

            if ($model) {
                $this->addError('slug', 'Dublicate slug - ' . $slug);
                return false;
            }

            $this->slug = $slug;
            return true;

        } else {
            return false;
        }
    }


    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        // Поиск существующей записи для категории или запись новой
        $productsCategories = ProductsCategories::find()->where(['product_id' => $this->id])->one();

        if (!$productsCategories) {
            $productsCategories = new ProductsCategories();
        }

        $productsCategories->category_id = $this->category;
        $productsCategories->product_id = $this->id;
        $productsCategories->save();

        // Сохранение изображений товара
        $images = UploadedFile::getInstances($this, 'images');

        if ($images) {

            $path = '/storage/products/' . $this->id . '/';

            // Загружаем изображение в папку с id товара
            $dir = Yii::getAlias('@frontend/web' . $path);
            $this->createDirectory($dir);

            foreach ($images as $image) {

                if (!$image->tempName) {
                    continue;
                }

                // Получение префикса для уникализации файла
                $prefix = Yii::$app->getSecurity()->generateRandomString(5);

                // Запись уникального имени для нового файла
                $fileName = $prefix . '_product_' . $this->id . '.' . $image->extension;
                $image->saveAs($dir . $fileName);

                // Запись в БД ссылок на изображения
                $productImage = new ProductImage();
                $productImage->product_id = $this->id;
                $productImage->path = $path . $fileName;
                $productImage->save();
            }
        }

        // Запись свойства для основного изображения

        if ($this->loadedImages) {
            // Находим предыдущее главное изображение, если оно было и изменяем его состояние
            $productImage = ProductImage::find()->where(['product_id' => $this->id, 'main' => 1])->one();

            if ($productImage) {
                $productImage->main = null;
                $productImage->save();
            }

            $productImage = ProductImage::findOne($this->loadedImages);

            if ($productImage) {
                $productImage->main = 1;
                $productImage->save();
            }
        }

        // Удаление изображений
        if ($this->deleteImages) {
            $deleteImages = ProductImage::find()->where(['id' => $this->deleteImages])->all();

            // Физически удаляем файлы изображения

            if ($deleteImages) {
                foreach ($deleteImages as $image) {
                    $path = Yii::getAlias('@frontend/web' . $image->path);

                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }

            // Удаляем записи об изображениях в БД
            ProductImage::deleteAll(['id' => $this->deleteImages]);
        }

    }


    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            // Удаление папки с изображениями физически

            $path = '/storage/products/' . $this->id . '/';
            $dir = Yii::getAlias('@frontend/web' . $path);

            $this->deleteDirectory($dir);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preview_text', 'full_description'], 'string'],
            [['name', 'price'], 'string', 'max' => 255],
            ['id', 'integer'],
            ['category', 'integer'],
            ['name', 'required'],
            ['status', 'boolean'],
            ['meta_description', 'string'],
            ['title', 'string'],
            ['h1', 'string'],
            ['auto', 'string'],
            [['discount_id', 'brand_id'], 'integer'],
            [['images'], 'file', 'extensions' => 'png, jpg', 'maxFiles' => '10'],
            [['loadedImages', 'deleteImages', 'code', 'category', 'model', 'unit', 'title', 'meta_description', 'h1', 'auto'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'slug' => 'Ссылка',
            'price' => 'Цена',
            'preview_text' => 'Текст',
            'category' => 'Категория',
            'full_description' => 'Описание',
            'images' => 'Изображения',
            'loadedImages' => 'Главное изображение',
            'deleteImages' => 'Удалить изображение',
            'discount_id' => 'Скидка',
            'brand_id' => 'Бренд',
            'code' => 'Код товара',
            'model' => 'Модель',
            'meta_description' => 'Description',
            'title' => 'Title',
            'unit' => 'Ед. изм.',
            'auto' => 'Автообновление',
        ];
    }

    public function search()
    {

        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load(Yii::$app->request->queryParams);

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['price' => $this->price]);
        $query->andFilterWhere(['like', 'name', (string)$this->name]);

        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }

    public function getCategoryLink()
    {
        return $this->hasOne(ProductsCategories::class, ['product_id' => 'id']);
    }

    public function getProductImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    public function getMainImage()
    {

        $images = $this->productImages;

        $mainImage = '';

        foreach ($images as $image) {
            if ($image->main) {
                return $image->path;
            }
        }

        // Если главное изображение не найдено, то берем случайное изображение
        $count = count($images);

        // Если изображения существуют, берем первое попавшееся
        if ($count) {
            $randId = rand(0, $count-1);
            return $images[$randId]->path;
        } else {
            // Если изображения нет, используем изображение-заглушку
            return $this->noImageProduct;
        }


    }


    public function getCategoriesDropDown() {
        $categories = Category::find()->all();
        return ArrayHelper::map($categories, 'id' , 'name');
    }

    public function getCategoriesDropDownParams($selected = 0) {
        return [
            'prompt' => 'Выберите категорию',
            'value' => $selected
        ];
    }

    public function getBrandsDropDown() {
        $brands = Brand::find()->all();
        return ArrayHelper::map($brands, 'id' , 'name');
    }

    public function getBrandsDropDownParams($selected = 0) {
        return [
            'prompt' => 'Выберите бренд',
            'value' => $selected
        ];
    }

    public function createDirectory($path) {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }

    protected function deleteDirectory($dir) {
        if ($objects = glob($dir."/*")) {
            foreach($objects as $object) {
                is_dir($object) ? $this->deleteDirectory($object) : unlink($object);
            }
        }

        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    public function getDiscountsList() {
        $discounts = ProductDiscount::find()->all();
        return ArrayHelper::map($discounts, 'id', 'name');
    }

    public function getDiscount()
    {
        return $this->hasOne(ProductDiscount::class, ['id' => 'discount_id']);
    }

    public function getBrand()
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    /*
     * Функция получает список опций и значений товара
     */
    public function getValues()
    {
        return $this->hasMany(ProductValue::class, ['product_id' => 'id']);
    }


    public static function saveOptionValue($productId, $optionName, $optionValue)
    {

        $option = ProductOption::findOne(['name' => $optionName]);

        if (empty($option)) {
            $option = new ProductOption();
            $option->name = $optionName;
            $option->save();
        }

        $option_value = ProductOptionValue::findOne(['name' => $optionValue]);

        if (empty($option_value)) {
            $option_value = new ProductOptionValue();
            $option_value->option_id = $option->id;
            $option_value->name = $optionValue;
            $option_value->save();
        }

        $product_value = ProductValue::findOne(['product_id' => $productId, 'option_id' => $option->id, 'value_id' => $option_value->id] );

        if (empty($product_value)) {
            $product_value = new ProductValue();
            $product_value->product_id = $productId;
            $product_value->option_id = $option->id;
            $product_value->value_id = $option_value->id;
            $product_value->save();
        }

    }
}
