<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $image backend\widgets\AdminGallery\Image */
?>
<?= Html::img($image ? $image->getUrl('preview') : '#{preview}', [
    'width' => $this->context->imageWidth ? $this->context->imageWidth : false,
    'height' => $this->context->imageHeight ? $this->context->imageHeight : false,
]) ?>

<input type="text" value="<?= $image ? $image->alt : '' ?>" class="image-alt-input" placeholder="Alt" data-image-id="<?= $image ? $image->id : '{id}' ?>"/>

<label for="ajax-delete-image-checkbox-<?= $image ? $image->id : '{id}' ?>" class="delete-image-label"><?= $this->context->messages['deleteCheckboxLabel'] ?>
    <input type="checkbox"
           class="delete-image-checkbox" id="ajax-delete-image-checkbox-<?= $image ? $image->id : '{id}' ?>">
	<span class="checkmark"></span>
</label>
