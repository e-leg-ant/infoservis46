<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\InformationCategory */
/* @var $form yii\widgets\ActiveForm */

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

<div class="information-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['style' => 'width: 100%;', 'rows' => 1, 'id' => 'information-category-description']) ?>

    <?php echo \vova07\imperavi\Widget::widget([
        'selector' => '#information-category-description',
        'settings' => $widget_settings,
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?php

    if (isset($model->image) && file_exists(Yii::getAlias('@frontend') . '/web' . $model->image))
    {
        echo Html::img(Yii::$app->urlManagerFrontend->baseUrl . $model->image, ['class'=>'img-responsive']);
    }

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
