<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = 'Создать запись';
$this->params['breadcrumbs'][] = ['label' => 'Группы контента', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="information-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
