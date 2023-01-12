<?php
use app\models\Order;
use app\models\CatalogItem;

$total = 0;
$delivery_total  = 0;

/** @var Controller $this */
/** @var int $id */

$this->title = 'Заказ №' . $id;
?>

<h2><?php echo $this->title; ?></h2>

<?php if (!empty($yandex_delivery_order)) : ?>
<h5 style="text-align:right;">Номер заказа в Яндекс.Доставка: <?php echo $yandex_delivery_order; ?></h5>
<?php endif; ?>

<table border="0" cellpadding="2" cellspacing="0" width="100%" class="table__print">
<tr>
    <th>Наименование товара</th>
    <th>Количество</th>
    <th>Стоимость</th>
    <th>Сумма</th>
</tr>
<?php foreach (/** @var Array $items */ $items as $position) : ?>
    <?php if (!in_array(preg_replace('~\D+~', '', $position['id']), array(CatalogItem::YESCREDIT_START_SUM, CatalogItem::YESCREDIT_ITEM_ID, CatalogItem::DELIVERY_ITEM_ID, CatalogItem::DELIVERY_EXPRESS_ITEM_ID ))) : ?>
        <tr>
            <td><?php echo iconv('windows-1251','utf-8', $position['name']); ?></td>
            <td><?php echo $position['qty']; ?></td>
            <td><?php echo $position['price']; ?></td>
            <td><?php echo $position['amount']; ?></td>
        </tr>
    <?php $total += $position['amount']; ?>
    <?php elseif (in_array(preg_replace('~\D+~', '', $position['id']), array(CatalogItem::DELIVERY_ITEM_ID, CatalogItem::DELIVERY_EXPRESS_ITEM_ID ))) : ?>
        <?php $delivery_total += $position['price']; ?>
    <?php endif; ?>
<?php endforeach; ?>
    <?php if (0 < $delivery_total) : ?>
    <tr><td>Стоимость доставки</td><td>&nbsp;</td><td>&nbsp;</td><td><?php echo $delivery_total; ?></td></tr>
    <?php endif; ?>
</table>
<br />
<?php if (0 < $percent) : ?>
<h4 style="text-align:right;">Скидка: <?php echo $percent; ?>%</h4>
<?php endif; ?>
<h3 style="text-align:right;">Итого: <?php echo ($total + $delivery_total); ?> руб</h3>

<?php if (!empty($promo) && 10 == strlen($promo)) : ?>
<p style="text-align:center; margin-top:30px; font-size: 20px;">
    <b>СКИДКА 2% НА СЛЕДУЮЩУЮ ПОКУПКУ!</b><br/> Для получения скидки на сайте магазина,<br/> в корзине заказов, введите промокод:
    <div class="promo-container">
        <div><?php echo $promo; ?></div>
        <span>к&nbsp;&nbsp;&nbsp;о&nbsp;&nbsp;&nbsp;д</span>
    </div>
</p>
<?php endif; ?>
