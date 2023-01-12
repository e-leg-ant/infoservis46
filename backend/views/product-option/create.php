<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProductOption */

$this->title = 'Создать характеристику товара';
$this->params['breadcrumbs'][] = ['label' => 'Характеристики товара', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-option-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
