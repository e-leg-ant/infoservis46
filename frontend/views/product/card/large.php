<?php
use yii\helpers\Url;

$mainImage = $product->getMainImage();

?>

<div class="item">

    <a href="<?= $url; ?>" class="pimg"><img src="<?= $mainImage; ?>"></a>

    <a href="<?= $url; ?>" class="pname"><?= $product->name; ?></a>

    <?php if (!empty($product->status)) : ?>
        <span class="pprice">от <?=$product->price;?> руб/шт.</span>
    <?php else: ?>
        <span class="pprice">Нет в наличии</span>
    <?php endif; ?>

    <div class="cartuse">

        <div class="cartbut">
            <a href="<?= Url::to(['/cart/put/', 'id' => $product->id]); ?>" class="buy-btn buybtn" data-price="<?=$product->price;?>" data-item-id="<?=$product->id;?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 21 21">
                    <path d="M19.254 12.359l1.724-8.531c.124-.616-.292-1.203-.853-1.203H5.805L5.47.787C5.387.33 5.03 0 4.613 0H.875C.392 0 0 .44 0 .984v.657c0 .543.392.984.875.984h2.548l2.56 14.086c-.612.397-1.025 1.14-1.025 1.992C4.958 19.972 5.872 21 7 21c1.128 0 2.042-1.028 2.042-2.297 0-.643-.235-1.224-.614-1.64h7.644a2.438 2.438 0 00-.614 1.64c0 1.269.914 2.297 2.042 2.297 1.128 0 2.042-1.028 2.042-2.297 0-.91-.47-1.695-1.152-2.067l.201-.996c.125-.616-.291-1.203-.853-1.203H7.952l-.238-1.312H18.4c.409 0 .763-.318.853-.766z"/>
                </svg>
                <span>Создать заявку</span>
            </a>
        </div>

    </div>

</div>
