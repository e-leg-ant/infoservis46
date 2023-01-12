<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\helpers\BaseInflector;

/**
 * This is the model class for table "information_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $title
 * @property string $meta_description
 * @property string $slug
 * @property string $image
 * @property string $h1
 */
class InformationCategory extends \yii\db\ActiveRecord
{

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'information_category';
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

        if (!empty($this->image)) {

            $path = Yii::getAlias('@frontend/web') . $this->image;

            // Удаление файла, только если он существует физически
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        return parent::beforeDelete();
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
           /* [['image', 'file'], 'required', 'on' => 'create'],*/
            [['file'], 'file', 'extensions' => 'png, jpg'],
            [['image'], 'string', 'max' => 255],
            [['name','title','description','meta_description', 'h1'], 'string', 'max' => 255],
            ['name, title, description, meta_description, h1', 'safe'],
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
            'description' => 'Описание',
            'title' => 'Seo Title',
            'slug' => 'Ссылка',
            'file' => 'Изображение',
            'meta_description' => 'Seo Description',
            'h1' => 'H1'
        ];
    }

    /**
     * Получение данных таблицы Information
     */

    public function getInformation()
    {
        return $this->hasMany(Information::class, ['category_id' => 'id']);
    }


}
