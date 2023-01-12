<?php

use frontend\widgets\InformationList\InformationList;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = (!empty($category->title)) ? $category->title  : 'Информация';

$this->params['breadcrumbs'] = $breadcrumbs;

?>

<h3><?= $this->title; ?></h3>

<?= InformationList::widget(['category' => $category->id, 'h1' => 'h2', 'view' => 'list']); ?>

<div class="wysiwyg">
    <?= $category->description; ?>
</div>
