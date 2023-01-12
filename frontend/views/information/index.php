<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use frontend\widgets\Gallery\Gallery;

$this->title = $information->title;

$this->params['breadcrumbs'] = $breadcrumbs;

?>

<div class="article">

    <h3><?= $information->name; ?></h3>

    <?php if (!empty($information->linkGallery)) : ?>

        <?php foreach ($information->linkGallery as $link) : ?>

            <?= Gallery::widget(['id' => $link->gallery_id, 'view' => 'article']); ?>

        <?php endforeach; ?>

    <?php endif; ?>

    <div class="parag">
        <p>
            <?= $information->full_description; ?>
        </p>
    </div>

    <?= \Yii::$app->view->renderFile('@frontend/views/forms/callback.php'); ?>

</div>

