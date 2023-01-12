<?php

use yii\helpers\Html;
use common\widgets\Categories\Categories;
use yii\helpers\Url;
use yii\web\View;

$this->title = (!empty($product->title)) ? $product->title : $product->name;

$this->params['breadcrumbs'] = $breadcrumbs;

$this->registerJsFile('/js/lightboxed.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="tablichki_product">

    <h1><?= (!empty($product->h1)) ? $product->h1 : $product->name; ?></h1>

    <div class="card">

        <?php if ($product->productImages) : ?>

            <?php foreach ($product->productImages as $image) : ?>

                <div class="atlant_product_images images" data-thumb="<?= $image->path; ?>">
                    <a href="<?= $image->path; ?>">
                        <img src="<?= $image->path; ?>" class="lightboxed" rel="group1">
                    </a>
                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <div class="atlant_product_images images" data-thumb="<?= $product->noImageProduct; ?>">
                <a href="<?= $product->noImageProduct; ?>">
                    <img src="<?= $product->noImageProduct; ?>" class="lightboxed" rel="group1">
                </a>
            </div>

        <?php endif; ?>

        <div class="desc">

            <div class="dsctext">

                <?= nl2br(Html::encode($product->full_description)); ?>

                <?php if (!empty($product->values)) : ?>

                    <div class="specifications chartext">

                        <?php foreach ($product->values as $pv) : ?>
                        <div class="item">
                            <span><?= $pv->option->name; ?></span>
                            <span><?= $pv->value->name; ?></span>
                        </div>
                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

        <div class="carduse">

            <div class="price">
                <?= $product->price;?> руб/шт.
            </div>

            <div class="pcount qty-count">

                <?php if (empty($product->status)) : ?>
                    Нет в наличии
                <?php else: ?>
                    <a href="#" class="minus value-minus">-</a>
                    <input type="text" value="1" class="product-quantity" name="quantity">
                    <a href="#" class="plus value-plus">+</a>
                <?php endif; ?>

            </div>

            <div class="addcart">
                <?php if (!empty($product->status)) : ?>
                    <a href="<?= Url::to(['/cart/put/', 'id' => $product->id]); ?>" class="add-to buy-btn hvr-skew-backward" data-price="<?=$product->price;?>" data-item-id="<?=$product->id;?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 21 21">
                            <path d="M19.254 12.359l1.724-8.531c.124-.616-.292-1.203-.853-1.203H5.805L5.47.787C5.387.33 5.03 0 4.613 0H.875C.392 0 0 .44 0 .984v.657c0 .543.392.984.875.984h2.548l2.56 14.086c-.612.397-1.025 1.14-1.025 1.992C4.958 19.972 5.872 21 7 21c1.128 0 2.042-1.028 2.042-2.297 0-.643-.235-1.224-.614-1.64h7.644a2.438 2.438 0 00-.614 1.64c0 1.269.914 2.297 2.042 2.297 1.128 0 2.042-1.028 2.042-2.297 0-.91-.47-1.695-1.152-2.067l.201-.996c.125-.616-.291-1.203-.853-1.203H7.952l-.238-1.312H18.4c.409 0 .763-.318.853-.766z"></path>
                        </svg>
                        <span>Добавить в корзину</span>
                    </a>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>

<?php

$initProductIndex = <<< JS

$('.value-plus').on('click', function(){
    var divUpd = $(this).parent().find('.product-quantity'), newVal = parseInt(divUpd.val(), 10)+1;
    divUpd.val(newVal);
    return false;
});

$('.value-minus').on('click', function(){
    var divUpd = $(this).parent().find('.product-quantity'), newVal = parseInt(divUpd.val(), 10)-1;
    if(newVal>=1) divUpd.val(newVal);
    return false;
});

JS;

$this->registerJs($initProductIndex, View::POS_READY, 'initProductIndex');
