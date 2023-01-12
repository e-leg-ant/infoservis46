<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .order-products {
        border: 1px solid #cccccc;
        width: 100%;
        padding: 5px;
    }
    .order-products td, .order-products th {
        border: 1px solid #cccccc;
        padding: 5px;
    }

</style>

<?php  $form = ActiveForm::begin([
    'options' => [
        'id' => 'order-form'
    ]
]);
?>

<h2 style="text-align: center">Заказ № <?= $model->id; ?> от <?= $model->date; ?></h2>

<?= $form->field($model, 'client'); ?>
<?= $form->field($model, 'mobile_phone'); ?>
<?= $form->field($model, 'phone'); ?>
<?= $form->field($model, 'email'); ?>
<?= $form->field($model, 'organization'); ?>
<?= $form->field($model, 'inn'); ?>
<?= $form->field($model, 'city'); ?>
<?= $form->field($model, 'address'); ?>
<?= $form->field($model, 'total'); ?>
<?= $form->field($model, 'comment'); ?>

<?php if (!empty($model->products) && is_array($model->products)) : ?>

<h2 style="text-align: center;">Товары</h2>

<table class="order-products" >

    <tr><th>Название</th><th>Количество</th><th>Цена</th><th>Итого</th></tr>

    <?php foreach ($model->products as $product) : ?>

    <tr>

        <td><?= $product->name; ?></td>
        <td><?= $product->quantity; ?></td>
        <td><?= $product->price; ?></td>
        <td><?= $product->amount; ?></td>

    </tr>

    <?php endforeach; ?>

</table>

<?php endif; ?>

<br/><br/><br/>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

