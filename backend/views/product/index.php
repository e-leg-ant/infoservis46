<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => [
            'id',
            'name',
            [
                'label' => 'Категория',
                'value' => function($item) {
                    return (!empty($item->categoryLink) ? $item->categoryLink->category->name : '');
                },
                'filter' => ''
            ],
            'price',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
