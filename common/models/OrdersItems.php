<?php

namespace common\models;

use common\widgets\Categories\Categories;
use Yii;
use yii\helpers\BaseInflector;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "orders_items".
 *
 * @property string $id_order
 * @property string $id_item
 * @property string $name
 * @property float $price
 * @property integer $quantity
 * @property float $amount

 */
class OrdersItems extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_items';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id_order', 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_item' => 'ID товара',
            'id_order' => 'ID заказа',
            'name' => 'Название',
            'price' => 'Цена',
            'quantity' => 'Количество',
            'amount' => 'Сумма',
        ];
    }

}
