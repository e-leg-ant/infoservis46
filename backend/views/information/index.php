<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Контент';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="information-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'slug',
            [
                'label' => 'Группа',
                'value' => 'category.name'
            ],
            'create',
            'order',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>