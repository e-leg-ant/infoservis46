<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\ProductOption */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Характеристики товара', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-option-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сохранить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту позицию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php

        // Вывод данных опции
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'sort',
            ],
        ]);

        echo Html::tag('h3', 'Значения характеристик');

        // Вывод значений опции
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'options' => ['class' => 'grid-view row col-md-4'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
            ],
        ]);

    ?>



</div>
