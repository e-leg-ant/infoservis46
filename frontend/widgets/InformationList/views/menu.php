<?php

use yii\helpers\Url;

?>

<?php if (!empty($information)) : ?>

    <ul class="submenu">

        <?php foreach ($information as $item) : ?>

            <li><a href="<?= Url::to(['information/index', 'category' => $category->slug, 'slug' => $item->slug]); ?>"><?=$item->name; ?></a></li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>