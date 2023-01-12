<?php

use yii\helpers\Url;

?>

<?php if (!empty($information)) : ?>

    <div class="second_menu"">

        <div class="inflinks">

            <div class="_inflinks">

            <?php foreach ($information as $item) : ?>

                <div class="item">
                    <a href="<?= Url::to(['information/index', 'category' => $category->slug, 'slug' => $item->slug]); ?>">
                        <img src="<?= $item->image; ?>">
                        <span><?=$item->name; ?></span>
                    </a>
                </div>

            <?php endforeach; ?>

            </div>

        </div>

    </div>

<?php endif; ?>