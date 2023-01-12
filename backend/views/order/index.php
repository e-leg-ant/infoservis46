<?php

use common\models\OrdersStatus;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Brand;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
yii\bootstrap\Modal::begin([
    'id' => 'modal-order',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modal-order-content'></div>";
yii\bootstrap\Modal::end();
?>

<?php
yii\bootstrap\Modal::begin([
    'id' => 'modal-order-status',
    'size' => 'modal-small',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modal-order-status-content'></div>";
yii\bootstrap\Modal::end();
?>

<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            'id',
            'client',
            'mobile_phone',
            'phone',
            'email',
            'organization',
            'inn',
            'total',
            'date',
            [
                'label' => 'Статус',
                'format' => 'html',
                'value' => function($item) {

                    return Html::a(OrdersStatus::getLastStatusLabel($item->id),
                        ['order/status', 'id' => $item->id],
                        ['class' => 'btn btn-default btn-xs order-status-btn order-status-btn-' . $item->id]
                    );
                }
            ],
            [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                ['order/form', 'id' => $key],
                                ['class' => 'btn btn-default btn-xs order-update-btn']
                            );
                        },

                    ],
                    'template' => '{update} '
            ],
        ],
    ]); ?>
</div>

<?php

$initLoadOrderForm = <<< JS

    $(document).on('click', '.order-update-btn', function(){
        
        $('#modal-order').modal();
        
        $('#modal-order-content').load($(this).attr('href'));
        
        return false;
        
    });

    $(document).on('click', '.order-status-btn', function(){
            
            $('#modal-order-status').modal();
            
            $('#modal-order-status-content').load($(this).attr('href'));
            
            return false;
            
        });
JS;

$this->registerJs($initLoadOrderForm, View::POS_READY, 'initLoadOrderForm');
