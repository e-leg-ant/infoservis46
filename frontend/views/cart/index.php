<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\Spinner;
use yii\bootstrap\Button;
use app\models\Order;

$title = 'Корзина';

$this->title = $title;
$this->params['breadcrumbs'] = [$title];

$sumFormatter = function ($num) { return number_format($num, 0, ',', ' '); };

?>

<div class="basket__order">

    <h3><?php echo $title; ?></h3>

    <label class="line"></label>

    <?php if (Yii::$app->cart->getIsEmpty()) : ?>
        <div class="error">Наполните Вашу корзину.</div>
    <?php else : ?>
        <?= Html::beginForm(['cart/index'], 'post', ['class' => 'basket__order-form']) ?>

    <table class="basket__order-table">

        <tr class="basket__order-table-row basket__order-table-row_head">
            <td class="basket__order-table-cell td0">Фото</td>
            <td class="basket__order-table-cell td1">Наименование</td>
            <td class="basket__order-table-cell td2">Кол-во</td>
            <!--<td class="basket__order-table-cell td3">Цена</td>
            <td class="basket__order-table-cell td4">Сумма</td>
            <td class="basket__order-table-cell td5">Скидка</td>-->
            <td class="basket__order-table-cell td6">&nbsp;</td>
        </tr>

        <?php
            $r = 0;
            $discount_price_total = 0;
        ?>

        <?php foreach (Yii::$app->cart->getPositions() as /** @var CatalogItem $position */ $position) : ?>

        <tr class="basket__order-table-row">

            <td class="basket__order-table-cell td0">

                <img src="<?= $position->getMainImage(); ?>" data-imagezoom="<?= $position->getMainImage(); ?>" class="img-responsive">

            </td>

            <td class="basket__order-table-cell td1">
                <?= Html::encode($position->name); ?>
            </td>

            <?php
            $price = $position->getPrice();
            $sum_price = $position->getCost();
            ?>

            <td class="basket__order-table-cell td2">
                <?php
                echo Spinner::widget([
                    'name'  => 'quantity[' . $position->getId() . ']',
                    'value' => $position->getQuantity(),
                    'clientOptions' => [
                        'min' => 1,
                        'max' => 999,
                        'disabled' => false,
                        'classes' => [
                            'ui-spinner-down' => 'ui-icon ui-icon-triangle-1-s',
                            'ui-spinner-up' => 'ui-icon ui-icon-triangle-1-n'
                        ]
                    ],
                    'options' => [
                        'id' => $position->getId(),
                        'class' => 'basket__order-number',
                        'data-name' => $position->name,
                        'data-price' => $position->price,
                        'data-item-id' => $position->id,
                    ]
                ]);
                ?>
            </td>

            <!--<td class="basket__order-table-cell td3"><?= $sumFormatter($price); ?></td>

            <td class="basket__order-table-cell td4"><?= $sumFormatter($sum_price); ?></td>

            <td class="basket__order-table-cell td5"><?= (!empty($position->discount) ? $position->discount->name : ''); ?></td>-->

            <?php $remove = '<a href="' . Url::to(['/cart/remove/', 'id' => $position->getId()]) . '" class="basket__order-remove" data-item-id="' . $position->id . '" data-name="' . Html::encode($position->name) . '"></a>';  ?>

            <td class="basket__order-table-cell td6"><?php echo $remove; ?></td>

        </tr>

        <?php endforeach; ?>

    </table>

        <!--<div class="basket__result">
            <span class="basket__result-title">Итого:</span>
            <span class="basket__result-total"><b><?php echo $sumFormatter(Yii::$app->cart->getCost(true)); ?></b> руб</span>

        </div>-->


            <div class="basket__steps">

                <div class="basket__steps-left">

                    <?= Button::widget([
                        'label' => 'Очистить корзину',
                        'options' => [
                            'class' => 'main__button main__button_grey',
                            'href' => Url::to(['/cart/empty/']),
                        ],
                        'tagName' => 'a'
                    ]); ?>

                    <?= Button::widget([
                        'label' => 'Продолжить покупки',
                        'options' => [
                            'class' => 'main__button main__button_green',
                            'href' => Url::home(),
                        ],
                        'tagName' => 'a'
                    ]); ?>

                </div>

                <div class="basket__steps-right">

                    <?= Button::widget([
                        'label' => 'ОФОРМИТЬ ЗАКАЗ',
                        'options' => [
                            'class' => 'main__button main__button_red basket__steps-confirm-button',
                            'href' => Url::to(['/cart/order/']),
                            'style' => 'float:right; font-weight:bold;',
                        ],
                        'tagName' => 'a'
                    ]); ?>

                </div>
            </div>

        <div class="" style="position: inherit; float: none; color: #ffffff;">Нажимая кнопку «ОФОРМИТЬ ЗАКАЗ», я соглашаюсь на получение информации от интернет-магазина и уведомлений о состоянии моих заказов, а также принимаю <?= Html::a('условия политики конфиденциальности', Url::to(['/information/informacziya/politika-konfidenczialnosti']), ['class' => '', 'style' => 'text-decoration:underline;', 'target' => '_blank']); ?> и <?= Html::a('пользовательского соглашения', Url::to(['/information/informacziya/polzovatelskoe-soglashenie']), ['class' => '', 'style' => 'text-decoration:underline;', 'target' => '_blank']); ?>.</div>

        <div class="basket__steps-remarks" style="display: none;">
            <div id="flash-messages-container"><div class="alert alert-danger" style="display:none;"></div></div>
        </div>


        <?= Html::endForm() ?>


<?php endif; ?>

</div>

<?php

$initBasketPage = <<< JS

    $(document).bind('keypress', function(event){
        if (13 == event.keyCode) {
            window.location.replace('/cart/order');
        }
    });

    $('.basket__order-number').on('spinstop', function( event, ui ) {
        $('.basket__order-form').submit();
    } );
    
    
    $('.catalog__item-buy-small-btn').click(function () {
        setTimeout(function () { window.location.replace('/cart/index'); }, 1000);
    });

    $('.basket__remove-item').click(function () {
        
        var owner = $(this);

        if (confirm('Удалить ' + owner.attr('data-name') + ' из корзины?')) {
        } else {
            return false;
        }
        return false;
    });
JS;

$this->registerJs($initBasketPage, View::POS_READY, 'initBasketPage');
?>