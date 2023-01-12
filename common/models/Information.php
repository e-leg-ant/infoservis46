<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\helpers\BaseInflector;

/**
 * This is the model class for table "information".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $full_description
 * @property string $title
 * @property string $meta_description
 * @property string $image
 * @property string $option
 * @property string $create
 * @property boolean status
 * @property integer id_category
 * @property string $slug
 * @property integer $order
 */
class Information extends \yii\db\ActiveRecord
{

    public $file;
    public $gallery;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'information';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->slug = BaseInflector::slug($this->name);
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete() {

        $path = Yii::getAlias('@frontend/web'). '/information/' . $this->image;

        // Удаление файла, только если он существует физически
        if (file_exists($path)) {
            unlink($path);
        }

        return parent::beforeDelete();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['name', 'category_id'], 'required'],
            [['status'], 'boolean'],
            [['name'], 'string', 'max' => 100],
            [['order'], 'integer'],
            [['image', 'title'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg'],
            [['option', 'description', 'full_description', 'meta_description'], 'string'],
            ['create, status, image, category_id, order', 'safe'],
            ['gallery', 'each', 'rule' => ['integer']],
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
            'status' => 'Статус',
            'title' => 'Title',
            'description' => 'Описание',
            'meta_description' => 'Meta Description',
            'full_description' => 'Полное описание',
            'create' => 'Дата создания',
            'option' => 'Специальный текст',
            'image' => 'Изображение',
            'category_id' => 'Группа',
            'slug' => 'Ссылка',
            'order' => 'Сортировка',
            'gallery' => 'Галлереи',
        ];
    }


    public function getCategory()
    {
        return $this->hasOne(InformationCategory::class, ['id' => 'category_id']);
    }

    public function getCategoriesDropDown() {
        $categories = InformationCategory::find()->all();
        return ArrayHelper::map($categories, 'id' , 'name');
    }

    public function getCategoriesDropDownParams($selected = 0) {
        return [
            'prompt' => 'Выберите группу',
            'value' => $selected
        ];
    }

    public function search()
    {

        $query = Information::find();

        $this->load(Yii::$app->request->queryParams);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $query->andFilterWhere(['like', 'name', (string)$this->name]);

        $query->orderBy(['id' => SORT_ASC]);

        return $dataProvider;
    }

    public function getLinkGallery()
    {
        return $this->hasMany(InformationGallery::class, ['information_id' => 'id']);
    }

    public function saveGallery()
    {
        if (isset($this->gallery)) {

            $result = InformationGallery::find()->where(['information_id' => $this->id])->all();

            if ($result) {

                foreach ($result as $row) {

                    if (!in_array($row->gallery_id, $this->gallery)) {
                        $row->delete();
                    }
                }
            }

            if (!empty($this->gallery)) {

                foreach ($this->gallery as $gallery_id) {

                    $modelInformationGallery = InformationGallery::find()->where(['information_id' => $this->id, 'gallery_id' => $gallery_id])->one();

                    if (empty($modelInformationGallery)) {
                        $modelInformationGallery = new InformationGallery();
                        $modelInformationGallery->information_id = $this->id;
                        $modelInformationGallery->gallery_id = $gallery_id;
                        $modelInformationGallery->save();
                    }
                }
            }

        }

    }

}
