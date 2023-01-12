<?php /* Категории */

use common\models\Category;use yii\helpers\Html;use yii\helpers\Url;

$today = date('Y-m-d');

?>

<?php /* Товары */ ?>
<?php foreach (/** @var CatalogItem[] $items */ $items as /** @var CatalogItem $item */ $item) : ?>

<?php
if (!empty($item->categoryLink->category_id)) {
    $category = Category::find()->where(['id' => $item->categoryLink->category_id])->one();
    $url = Url::to(['product/index', 'category' => $category->slug, 'slug' => $item->slug], true);
} else {
    $category = null;
    $url = Url::to(['product/index', 'slug' => $item->slug], true);
}
?>

<url>
    <loc><?php echo $url; ?></loc>
    <lastmod><?php echo $today; ?></lastmod>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
</url>

<?php endforeach; ?>