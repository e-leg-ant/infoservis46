<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\AdminGallery\GalleryAjaxWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Gallery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <div class="gallery-form">

        <?php echo GalleryAjaxWidget::widget([
            'model' => $model,
            'action' => ['gallery-ajax'],
        ]);?>

    </div>


    <?php ActiveForm::end(); ?>

</div>
