<?php

namespace common\models;

use common\models\ProductOption;

/**
 * This is the model class for table "products_options_values".
 *
 * @property integer $id
 * @property integer $option_id
 * @property string $name
 *
 * @property ProductOption $option
 */
class ProductOptionValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products_options_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name', 'option_id'], 'unique', 'targetAttribute' => ['name', 'option_id']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOption::class, 'targetAttribute' => ['option_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'option_id' => 'ID характеристики',
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(ProductOption::class, ['id' => 'option_id']);
    }
}
