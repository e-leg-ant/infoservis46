<?php

use yii\helpers\Html;

$this->title = $brand->title;

$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['site/brands']];

$this->params['breadcrumbs'][] = $brand->name;

?>

<div class="site-brand content-mid">

    <h1><?= Html::encode($brand->name) ?></h1>

    <label class="line"></label>

    <div style="text-align: center">
        <img src="<?=$brand->image;?>" alt="<?=$brand->alt;?>" style="margin: 1em;">
    </div>

    <div style="font-size: 120%; margin: 1em;">
        <?=$brand->description; ?>
    </div>


</div>