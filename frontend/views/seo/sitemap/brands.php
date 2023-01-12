<?php
use yii\helpers\Url;
?>
<?php $today = date('Y-m-d'); ?>
<?php foreach ($brands as $brand) : ?>
    <url>
        <loc><?=Url::to(['site/brands', 'id' => $brand->id], true); ?></loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
<?php endforeach; ?>

