<?php

use common\models\Category;
use common\models\Product;
use common\models\ProductImage;
use yii\helpers\Html;
use yii\helpers\Url;


$absoluteUrl = /** @var Controller $this */ Yii::$app->urlManager->getHostInfo();

$date = new DateTime();
$hour = $date->format('G');

$days = '5-7';

?>
<offer id="<?php echo $item->id; ?>" available="true" type="vendor.model">
<url><?php

$model = '';

$item_price = $item['price'];

$additional_images = ProductImage::find()->where(['product_id' => $item->id])->limit(9)->all();

$productCategory = \common\models\ProductsCategories::find()->where(['id' => $item->id])->one();

$category_id = (!empty($item->categoryLink->category_id)) ? $item->categoryLink->category_id : null;

if (!empty($category_id)) {
    $cardUrl = Url::to(['product/index', 'category' => (!empty($item->categoryLink->slug)) ? $item->categoryLink->slug : null, 'slug' => $item->slug], true);
} else {
    $cardUrl = Url::to(['product/index', 'slug' => $item->slug], true);
}

echo $cardUrl; ?></url>

<price><?php echo $item_price; ?></price>

<currencyId>RUR</currencyId>
<categoryId><?php echo $category_id; ?></categoryId>

<?php if (!empty($additional_images) && is_array($additional_images)) : ?>
<?php foreach ($additional_images as $img) : ?>
    <picture><?php echo $absoluteUrl . $img->path; ?></picture>
    <?php endforeach; ?>
<?php endif; ?>

<delivery>true</delivery>
<pickup>false</pickup>

<pickup-options>
    <option cost="0" days="<?php echo $days; ?>" order-before="15"/>
</pickup-options>

<?php if (!empty($item->brand->name)) : ?>
<vendor><?php echo Html::encode($item->brand->name); ?></vendor>
<?php endif; ?>

<description><?php echo Html::encode($item->full_description); ?></description>
<sales_notes>Варианты оплаты уточняйте в магазине.</sales_notes>
<manufacturer_warranty>true</manufacturer_warranty>

<?php /*if (!empty($item->values)) : ?>

    <?php foreach ($item->values as $pv) : ?>

        <param name="<?php echo Html::encode($pv->option->name); ?>" unit="">
            <?php echo Html::encode($pv->value->name); ?>
        </param>
    <?php endforeach; ?>
<?php endif;*/ ?>

</offer>