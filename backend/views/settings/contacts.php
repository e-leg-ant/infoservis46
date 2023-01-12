<?php

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Контакты';

$this->params['breadcrumbs'][] = $this->title;

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
<div class="mainpage-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= Html::beginForm(['settings/contacts'], 'post', ['enctype' => 'multipart/form-data']) ?>

        <h2>Наименование контакта</h2>
        <?= Html::textarea('data[config_contact_name]', (!empty($data['config_contact_name']) ? $data['config_contact_name'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_contact_name']); ?>

        <h2>Адрес</h2>
        <?= Html::textarea('data[config_address]', (!empty($data['config_address']) ? $data['config_address'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_address']); ?>

        <?php echo \vova07\imperavi\Widget::widget([
            'selector' => '#config_address',
            'settings' => $widget_settings,
        ]); ?>

        <h2>Код отображения карты проезда</h2>
        <?= Html::textarea('data[contact_map_code]', (!empty($data['contact_map_code']) ? $data['contact_map_code'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'contact_map_code']); ?>

        <h2>E-Mail</h2>
        <?= Html::textarea('data[config_email]', (!empty($data['config_email']) ? $data['config_email'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_email']); ?>

        <h2>Телефон</h2>
        <?= Html::textarea('data[config_telephone]', (!empty($data['config_telephone']) ? $data['config_telephone'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_telephone']); ?>

        <h2>Телефон 2</h2>
        <?= Html::textarea('data[config_fax]', (!empty($data['config_fax']) ? $data['config_fax'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_fax']); ?>

        <h2>Телефон 3</h2>
        <?= Html::textarea('data[config_telephone3]', (!empty($data['config_telephone3']) ? $data['config_telephone3'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_telephone3']); ?>

        <h2>Дополнительная информация</h2>
        <?= Html::textarea('data[config_contact_welcome]', (!empty($data['config_contact_welcome']) ? $data['config_contact_welcome'] : ''), ['style' => 'width: 100%;', 'rows' => 1, 'id' => 'config_contact_welcome']); ?>

        <?php echo \vova07\imperavi\Widget::widget([
            'selector' => '#config_contact_welcome',
            'settings' => $widget_settings,
        ]); ?>

    <?= Html::submitButton('Сохранить', ['class' => 'submit btn btn-success']) ?>
    <?= Html::a('Отмена', ['/site/index'], ['class'=>'btn btn-primary']) ?>

    <?= Html::endForm() ?>
</div>
