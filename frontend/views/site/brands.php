<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Бренды';

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-brands content-mid">

    <h1><?= Html::encode($this->title) ?></h1>

    <label class="line"></label>

    <table style="margin-top: 1em;">

        <?php foreach ($brands as $brand) : ?>

            <tr id="brand<?=$brand->id; ?>">
                <td style="width: 200px;">
                    <?php if (!empty($brand->image)) : ?>
                        <img src="<?=$brand->image;?>" alt="" style="width: 100%;">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?=Url::to(['site/brands', 'id' => $brand->id]); ?>" style="font-size: 150%;">
                        <?=$brand->name; ?>
                    </a>
                    <div style="font-size: 120%;"><?=$brand->description; ?></div>
                </td>
            </tr>

        <?php endforeach; ?>

        </tr>

    </table>


</div>