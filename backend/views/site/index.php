<?php

/* @var $this yii\web\View */

$this->title = 'Админка';

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

$widget_settings = [
    'lang' => 'ru',
    'minHeight' => 50,
    'imageUpload' => Url::to(['/settings/upload']),
    'imageManagerJson' => Url::to(['/settings/imagesget']),
    'imageDelete' => Url::to(['/settings/filedelete']),
    'plugins' => [
        'fullscreen',
        'imagemanager',
    ]
];

?>

<div class="site-index">

    <div class="body-content">

        <?= Html::beginForm(['site/index'], 'post', ['enctype' => 'multipart/form-data']) ?>

        <h2>Главная SEO - title</h2>
        <?= Html::textarea('data[config_main_seo_title]', (!empty($data['config_main_seo_title']) ? $data['config_main_seo_title'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_main_seo_description']); ?>

        <h2>Главная SEO - description</h2>
        <?= Html::textarea('data[config_main_seo_description]', (!empty($data['config_main_seo_description']) ? $data['config_main_seo_description'] : ''), ['style' => 'width: 100%;', 'rows' => 2, 'id' => 'config_main_seo_description']); ?>

        <h2>Текст на главной</h2>

        <?= Html::textarea('data[config_main_text]', (!empty($data['config_main_text']) ? $data['config_main_text'] : ''), ['style' => 'width: 100%;', 'rows' => 2, 'id' => 'config_main_text']); ?>

        <?php echo \vova07\imperavi\Widget::widget([
            'selector' => '#config_main_text',
            'settings' => $widget_settings,
        ]); ?>

        <h2>Текст Услуги</h2>

        <?= Html::textarea('data[config_about_text]', (!empty($data['config_about_text']) ? $data['config_about_text'] : ''), ['style' => 'width: 100%;', 'rows' => 2, 'id' => 'config_about_text']); ?>

        <?php echo \vova07\imperavi\Widget::widget([
            'selector' => '#config_about_text',
            'settings' => $widget_settings,
        ]); ?>

        <br/><br/>
        <?= Html::submitButton('Сохранить', ['class' => 'submit btn btn-success']) ?>

        <?= Html::endForm() ?>

    </div>

</div>
