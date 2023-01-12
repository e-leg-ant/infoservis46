<?php
use yii\helpers\Url;
?>
<?php /* Категории */ $today = date('Y-m-d'); ?>
<?php foreach (/** @var Category[] $categories */ $categories as /** @var Category $category */ $category) : ?>
    <url>
        <loc><?=Url::to(['category/index', 'slug' => $category->slug], true); ?></loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.75</priority>
    </url>
<?php endforeach; ?>

