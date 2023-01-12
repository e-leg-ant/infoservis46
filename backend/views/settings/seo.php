<?php

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SEO';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mainpage-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginForm(['settings/seo'], 'post', ['enctype' => 'multipart/form-data']) ?>

        <h2>Категории</h2>

        <div>Title</div>
        <?= Html::textInput('data[config_seo_category_title]', (!empty($data['config_seo_category_title']) ? $data['config_seo_category_title'] : ''), ['style' => 'width: 100%;', 'id' => 'config_seo_category_title']); ?>

        <div>Description</div>
        <?= Html::textInput('data[config_seo_category_description]', (!empty($data['config_seo_category_description']) ? $data['config_seo_category_description'] : ''), ['style' => 'width: 100%;', 'id' => 'config_seo_category_description']); ?>

        <div>H1</div>
        <?= Html::textInput('data[config_seo_category_h1]', (!empty($data['config_seo_category_h1']) ? $data['config_seo_category_h1'] : ''), ['style' => 'width: 100%;', 'id' => 'config_seo_category_h1']); ?>

    <br/><br/>

        <h2>Товары</h2>

        <div>Title</div>
        <?= Html::textInput('data[config_seo_product_title]', (!empty($data['config_seo_product_title']) ? $data['config_seo_product_title'] : ''), ['style' => 'width: 100%;', 'id' => 'config_seo_product_title']); ?>

        <div>Description</div>
        <?= Html::textInput('data[config_seo_product_description]', (!empty($data['config_seo_product_description']) ? $data['config_seo_product_description'] : ''), ['style' => 'width: 100%;', 'id' => 'config_seo_product_description']); ?>

        <div>H1</div>
        <?= Html::textInput('data[config_seo_product_h1]', (!empty($data['config_seo_product_h1']) ? $data['config_seo_product_h1'] : ''), ['style' => 'width: 100%;', 'id' => 'config_seo_product_h1']); ?>


    <br/><br/>

        <?= Html::submitButton('Сохранить', ['class' => 'submit btn btn-success']) ?>
        <?= Html::a('Отмена', ['/site/index'], ['class'=>'btn btn-primary']) ?>

    <?= Html::endForm() ?>
</div>
