<?php

use common\models\OrdersStatus;use common\models\OrderStatus;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

if (!empty($orderHistory) && is_array($orderHistory) && !empty($orderHistory[0]['status'])) {
    $last_order_status = $orderHistory[0]['status'];
} else {
    $last_order_status = '';
}

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .order-status {
        border: 1px solid #cccccc;
        width: 100%;
        padding: 5px;
    }
    .order-status td, .order-products th {
        border: 1px solid #cccccc;
        padding: 5px;
    }

</style>

<?php  $form = ActiveForm::begin([
    'options' => [
        'id' => 'order-status-form'
    ]
]);
?>

<h2 style="text-align: center">Заказ № <?= $modelOrder->id; ?> от <?= $modelOrder->date; ?></h2>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">

    <?= $form->field($modelOrder, 'id')->hiddenInput()->label(false); ?>
    <?= $form->field($modelOrder, 'status')->dropDownList(OrdersStatus::$statuses, ['value' => $last_order_status]); ?>

    <?= Html::submitButton('Изменить', ['class' => 'btn btn-success']) ?>
</div>


<?php ActiveForm::end(); ?>


<?php if (!empty($orderHistory) && is_array($orderHistory)) : ?>

<h2 style="text-align: center;">Статусы заказа</h2>

<table class="order-status" >

    <tr><th>Статус</th><th>Дата</th></tr>

    <?php foreach ($orderHistory as $status) : ?>

    <tr>

        <td><?= OrdersStatus::getLabel($status->status); ?></td>
        <td><?= $status->date; ?></td>

    </tr>

    <?php endforeach; ?>

</table>

<?php endif; ?>

<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    $('#order-status-form').on('beforeSubmit', function(){
        
        var data = $(this).serialize();
        $.ajax({
            url: $('#order-status-form').attr('action'),
            type: 'POST',
            data: data,
            success: function(data){
                
                var order_id = $('#order-status-form #order-id').val();
                var status = $('#order-status-form #order-status option:selected').text();

                $('.order-status-btn-' + order_id).html(status);
             
                $('#modal-order-status-content').html(data);
                
            },
            error: function(){

            }
        });
        
        return false;
    });
JS;

$this->registerJs($js);
?>

