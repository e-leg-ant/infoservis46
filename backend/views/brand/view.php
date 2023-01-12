<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Brand;

/* @var $this yii\web\View */
/* @var $model common\models\Brand */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'label' => 'Image',
                'format' => 'html',
                'value' => Html::img(Yii::$app->urlManagerFrontend->baseUrl . $model->image, ['class'=>'img-responsive'])
            ],
            'sort',
            [
                'label' => 'Status',
                'value' => $model->status ? Brand::STATUS_ACTIVE : Brand::STATUS_DISABLED
            ],

        ],
    ]) ?>

</div>
