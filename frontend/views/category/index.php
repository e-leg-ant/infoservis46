<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\widgets\CategoriesFilter\CategoriesFilter;

if (!empty($category->name)) {
    $this->title = (!empty($category->title)) ? $category->title : $category->name;
} else {
    $this->title = 'Каталог продукции';
}


$this->params['breadcrumbs'] = $breadcrumbs;

?>


    <h3><?= $this->title; ?></h3>

    <div class="tablichki_catalog">

    <div class="catmenu">

        <div class="tablichki_groups menu">

            <?php

            echo \yii\bootstrap\Nav::widget([
                'options' => [
                    'class' => '',
                    'style' => 'margin:5px'
                ],

                'items' => \common\models\Category::getTreeArray(0)

            ]);
            ?>
        </div>

    </div>

    <div class="flitm">

        <?php if ($products) : ?>

            <div class="tablichki_items catitems" data-id="tablichki_items-block">

                <div class="_catitems">

                    <?= $products; ?>

                    <?= LinkPager::widget(['pagination' => $pages]); ?>

                </div>

                <!--<?=CategoriesFilter::widget(
                    [
                        'category' => $category,
                        'brandsWhere' => $brandsWhere,
                        'discountsWhere' => $discountsWhere,
                    ]
                );?>-->

            </div>

        <?php endif; ?>

    </div>

</div>