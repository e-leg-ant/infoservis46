<?php

namespace common\models;

/**
 * This is the model class for table "information_gallery".
 *
 * @property integer $information_id
 * @property integer $gallery_id

 */
class InformationGallery extends \yii\db\ActiveRecord
{

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'information_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['information_id', 'gallery_id'], 'required'],
            [['gallery_id', 'information_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'information_id' => 'ID Контента',
            'gallery_id' => 'ID галереи',
        ];
    }

    public function getGallery()
    {
        return $this->hasMany(Gallery::class, ['id' => 'gallery_id']);
    }

    public function getInformation()
    {
        return $this->hasMany(Information::class, ['id' => 'information_id']);
    }

}