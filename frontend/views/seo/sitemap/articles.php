<?php
use yii\helpers\Html;
use yii\helpers\Url;

$categories = [];

?>

<?php /* Статьи */ $today = date('Y-m-d'); ?>

<?php foreach ($information_category as /** @var Article $article */ $category) : ?>

<?php $categories[$category->id] = $category; ?>
    <url>
        <loc><?= Url::to(['information/category', 'slug' => $category->slug], true); ?></loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
<?php endforeach; ?>

<?php foreach ($information as /** @var Article $article */ $information) : ?>
    <url>
        <loc><?php echo Url::to(['information/index', 'category' => $categories[$information->category_id]->slug, 'slug' => $information->slug], true ); ?></loc>
        <lastmod><?php list($articleDate, ) = explode(' ', $information->create, 2); echo $articleDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
<?php endforeach; ?>
