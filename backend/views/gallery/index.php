<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Галереи';

$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
