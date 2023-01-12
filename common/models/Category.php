<?php

namespace common\models;

use common\widgets\Categories\Categories;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\BaseInflector;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $parent_id
 * @property string $image
 * @property string $title
 * @property string $meta_descripton
 * @property string $external_id
 * @property string $h1
 * @property string $order
 */
class Category extends \yii\db\ActiveRecord
{

    public $file;

    public $noImage = '/storage/product-no-image.png';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->slug = self::getFullSlug($this->id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Удаление кэша виджета категорий после сохранения модели
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        // Обновление кэша для frontend-виджета
        if (Yii::$app->cacheFrontend->exists('categories')) {
            Yii::$app->cacheFrontend->delete('categories');
        }

    }

    public function beforeDelete() {

        $path = Yii::getAlias('@frontend/web') . $this->image;

        // Удаление файла, только если он существует физически
        if (file_exists($path)) {
            unlink($path);
        }

        return parent::beforeDelete();
    }

    /**
     * Удаление кэша виджета категорий после удаления модели
     */
    public function afterDelete()
    {
        parent::afterDelete();

        // Обновление кэша для frontend-виджета
        if (Yii::$app->cacheFrontend->exists('categories')) {
            Yii::$app->cacheFrontend->delete('categories');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['parent_id', 'order'], 'integer'],
            [['parent_id'], 'default', 'value' => 0],
            [['image', 'file'], 'required', 'on' => 'create'],
            [['file'], 'file', 'extensions' => 'png, jpg'],
            [['image'], 'string', 'max' => 255],
            ['meta_description', 'string'],
            ['title', 'string'],
            ['h1', 'string'],
            ['parent_id, external_id, title, meta_description, h1', 'safe']
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
            'slug' => 'Slug',
            'parent_id' => 'Родительский ID',
            'external_id' => 'Внешний ID',
            'title' => 'Title',
            'meta_description' => 'Description',
            'image' => 'Изображение',
            'order' => 'Сортировка',
        ];
    }

    /**
     * Получение дочерних категорий данной категории
     */
    public function getChildCategories()
    {
        return $this->hasMany(Category::class, ['parent_id' => 'id']);
    }

    /**
     * Получение дочерних категорий данной категории
     */
    public function getParent()
    {
        return $this->hasOne(Category::class, ['id' => 'parent_id']);
    }

    /**
     * Получение данных таблицы ProductsCategories
     */

    public function getProducts()
    {
        return $this->hasMany(ProductsCategories::class, ['category_id' => 'id']);
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

    /**
     * Получение списка товаров по категории
     */

    public function getAllProducts($mode = null)
    {
        // Получение id всех товаров по категории
        $productsCategory = $this->getProducts()->select(['product_id'])->asArray()->all();

        // Преобразование результатов в более удобный массив для запроса
        $productArray = ArrayHelper::getColumn($productsCategory, 'product_id');

        // Возвращаем несформированный запрос, если это необходимо

        $query = Product::find()->with('productImages')->where(['id' => $productArray])->orderBy('status desc');

        if ($mode == 'query') {
            return $query;
        } else {
            $products = $query->all();
        }

        return $products;
    }

    /**
     * Получение списка родительских категорий
     * (категории, у которых не заполнено поле parent_id)
     */

    public static function getParentsCategory()
    {
        return Category::findAll(['parent_id' => null]);
    }

    public function getImage()
    {

        if (empty($this->image)) {
            return $this->noImage;
        } else {
            return $this->image;
        }

    }

    /**
     * Получение всех дочерних категорий данной категории
     */
    public static function getAllChild($idCategory, $ids = [])
    {
        $ids[] = $idCategory;

        $categories = Category::find()->where(['parent_id' => $idCategory])->all();

        if (!empty($categories) && is_array($categories)) {

            foreach ($categories as $child) {

                $ids[] = $child->id;

                $ids = self::getAllChild($child->id, $ids);
            }

        }

        return $ids;

    }

    /**
     * Получение всех дочерних категорий данной категории
     */
    public static function getTreeArray($idCategory)
    {
        $items = [];

        $categories = Category::find()->where(['parent_id' => $idCategory])->all();

        if (!empty($categories) && is_array($categories)) {

            foreach ($categories as $child) {

                $items[] = [
                    'label' => $child->name,
                    'url'  => Url::to(['category/index', 'slug' => $child->slug]),
                    'items' => self::getTreeArray($child->id),
                ];

            }

        }

        return $items;

    }

    /**
     * Есть ли в категории и подкатегориях товары
     */
    public static function hasTreeProducts($idCtegory)
    {

        $ids = self::getAllChild($idCtegory);

        $categories_not_empty = ProductsCategories::find()->select('distinct `category_id` as category_id')->where(['in','category_id', $ids])->asArray()->all();

        return (!empty($categories_not_empty)) ? true : false;

    }

    /**
     * Получение slug до родительской дирректиории
     */
    public static function getFullSlug($idCategory, $slug = '')
    {

        $category = Category::find()->where(['id' => $idCategory])->one();

        if (!empty($category)) {

            $slug = BaseInflector::slug($category->name) . ((!empty($slug)) ? '-' . $slug : '');

            $slug = self::getFullSlug($category->parent_id, $slug);

        }

        return $slug;

    }


}
