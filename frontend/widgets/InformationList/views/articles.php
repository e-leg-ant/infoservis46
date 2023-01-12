<?php

use yii\helpers\Url;

?>

<?php if (!empty($information)) : ?>

    <div class="articles">

        <div class="incnt">
            <<?= $h1; ?>><?= (!empty($category->h1) ? $category->h1 : $category->name); ?></<?= $h1; ?>>
        </div>

        <div class="allcheckroom">

            <?php foreach ($information as $item) : ?>

                <a href="<?= Url::to(['information/index', 'category' => $category->slug, 'slug' => $item->slug]); ?>" class="item">
                    <?php if (!empty($item->image)) : ?>
                        <img src="<?=$item->image;?>" alt="">
                    <?php endif; ?>
                    <span><?=$item->name; ?></span>
                </a>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif; ?>
