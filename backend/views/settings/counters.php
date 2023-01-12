<?php

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счетчики';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mainpage-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= Html::beginForm(['settings/counters'], 'post', ['enctype' => 'multipart/form-data']) ?>

        <h2>Счетчик Yandex</h2>
        <?= Html::textarea('data[config_counter_yandex]', (!empty($data['config_counter_yandex']) ? $data['config_counter_yandex'] : ''), ['style' => 'width: 100%;', 'rows' => 5, 'id' => 'config_counter_yandex']); ?>

        <h2>Счетчик Google</h2>
        <?= Html::textarea('data[config_counter_google]', (!empty($data['config_counter_google']) ? $data['config_counter_google'] : ''), ['style' => 'width: 100%;', 'rows' => 5, 'id' => 'config_counter_google']); ?>

    <br/><br/>
    <?= Html::submitButton('Сохранить', ['class' => 'submit btn btn-success']) ?>
    <?= Html::a('Отмена', ['/site/index'], ['class'=>'btn btn-primary']) ?>

    <?= Html::endForm() ?>
</div>
