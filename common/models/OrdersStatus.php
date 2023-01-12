<?php

namespace common\models;

use common\widgets\Categories\Categories;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;

/**
 * This is the model class for table "orders_status".
 *
 * @property integer $id_status
 * @property string $id_order
 * @property string $status
 * @property string $date

 */
class OrdersStatus extends \yii\db\ActiveRecord
{

    const STATUS_NEW = 'new';

    public static $statuses = [
        'new' => 'Новый',
        'handle' => 'В обработке',
        'ready' => 'Готов',
        'canceled' => 'Отменен'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_status';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id_order', 'integer'],
            [['status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_status' => 'ID',
            'id_order' => 'ID заказа',
            'name' => 'Name',
            'status' => 'Статус',
            'date' => 'Дата',
        ];
    }

    public static function getLabel($status) {
        return (!empty(self::$statuses[$status])) ? self::$statuses[$status] : '';
    }

    /**
     * @inheritdoc
     */
    public static function getLastStatusLabel($idOrder)
    {
        $orderStatusModel = OrdersStatus::find()->where(['id_order' => $idOrder])->orderBy(['date' => SORT_DESC])->one();

        return (!empty($orderStatusModel->status) && !empty(self::$statuses[$orderStatusModel->status])) ? self::$statuses[$orderStatusModel->status] : '';
    }

}
