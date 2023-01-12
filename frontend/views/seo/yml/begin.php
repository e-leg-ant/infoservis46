<?php
header('Content-type: text/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

use yii\helpers\Html;
use app\models\Settings;
use app\models\CatalogItem;
use app\models\CatalogItemImages;

$absoluteUrl = /** @var Controller $this */ Yii::$app->urlManager->getHostInfo();

$deliveryCost = 1000;

?><!DOCTYPE yml_catalog SYSTEM "shops.dtd">
    <yml_catalog date="<?php echo date('Y-m-d H:i'); ?>">
        <shop>
            <name><?php echo Yii::$app->name; ?></name>
            <company><?php echo Yii::$app->name; ?></company>
            <url><?php echo $absoluteUrl; ?></url>
            <currencies>
                <currency id="RUR" rate="1"/>
            </currencies>
            <categories>
                <?php foreach (/** @var Category[] $categories */ $categories as /** @var Category $category */ $category) : ?>
                    <category id="<?php echo $category->id; ?>"<?php if (0 < $category->parent_id) : ?> parentId="<?php echo $category->parent_id; ?>"<?php endif; ?>><?php echo Html::encode($category->name); ?></category>
                <?php endforeach; ?>
            </categories>
            <delivery-options>
                <option cost="<?php echo $deliveryCost; ?>" days="1-2"/>
            </delivery-options>
            <offers>