<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'parent_id')->dropDownList($model->getCategoriesDropDown(), $model->getCategoriesDropDownParams($model->parent_id)) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'meta_description')->textInput() ?>

    <?= $form->field($model, 'order')->textInput(['type' => 'number']) ?>

    <?php

    if (isset($model->image) && file_exists(Yii::getAlias('@frontend') . '/web' . $model->image)) {
        echo Html::img(Yii::$app->urlManagerFrontend->baseUrl . $model->image, ['class'=>'img-responsive']);
    }

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
