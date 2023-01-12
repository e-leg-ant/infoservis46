<?php
use yii\helpers\Url;
?>


<url>
    <loc><?php echo Url::to(['/category/index'], true);?></loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.5</priority>
</url>

<url>
    <loc><?php echo Url::to(['/site/works'], true);?></loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.5</priority>
</url>

<url>
    <loc><?php echo Url::to(['/site/contacts'], true);?></loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.5</priority>
</url>

<url>
    <loc><?php echo Url::to(['/site/brands'], true);?></loc>
    <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.5</priority>
</url>



