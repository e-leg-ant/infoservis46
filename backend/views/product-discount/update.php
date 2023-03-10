<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductDiscount */

$this->title = 'Изменить скидки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="product-discount-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
