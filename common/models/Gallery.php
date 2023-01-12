<?php

namespace common\models;

use Yii;
use backend\widgets\AdminGallery\GalleryBehavior;
use yii\imagine\Image;

/**
 * This is the model class for table "gallery_images".
 *
 * @property integer $id

 */
class Gallery extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string']
        ];
    }

    public function behaviors()
    {
        return [

            [
                'class' => GalleryBehavior::class,
                'basePath' => Yii::getAlias('@frontend/web/storage/gallery'),
                'baseUrl' => Yii::$app->urlManagerFrontend->baseUrl . '/storage/gallery',
            ],


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
        ];
    }

    public function getImages()
    {
        return $this->hasMany(\backend\widgets\AdminGallery\Image::class, ['item_id' => 'id']);
    }


}
