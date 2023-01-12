<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \common\models\Gallery;
use \yii\helpers\ArrayHelper;

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

<div class="information-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList($model->getCategoriesDropDown(), $model->getCategoriesDropDownParams($model->category)) ?>

    <?= $form->field($model, 'status')->checkbox(['label' => 'Активный']) ?>

    <?= $form->field($model, 'order')->input('number') ?>

    <?= $form->field($model, 'option')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['style' => 'width: 100%;', 'rows' => 1, 'id' => 'information-description']) ?>

    <?php echo \vova07\imperavi\Widget::widget([
        'selector' => '#information-description',
        'settings' => $widget_settings,
    ]); ?>

    <?= $form->field($model, 'full_description')->textarea(['style' => 'width: 100%;', 'rows' => 3, 'id' => 'information-full_description']) ?>

    <?php echo \vova07\imperavi\Widget::widget([
        'selector' => '#information-full_description',
        'settings' => $widget_settings,
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?php

    if (isset($model->image) && file_exists(Yii::getAlias('@frontend') . '/web' . $model->image))
    {
        echo Html::img(Yii::$app->urlManagerFrontend->baseUrl . $model->image, ['class'=>'img-responsive']);
    }

    ?>
    <br/><br/>

    <?= $form->field($model, 'gallery')->checkboxList(ArrayHelper::map(Gallery::find()->all(), 'id' , 'name'), ['value' => ArrayHelper::getColumn($model->linkGallery, 'gallery_id') ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
