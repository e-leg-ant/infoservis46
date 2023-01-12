<?php
use yii\web\View;

$title = 'Оформление заказа';
$this->title = $title;
$this->params['breadcrumbs'] = [$title];

?>

<div class="cart__end">

<?php if (Yii::$app->cart->getIsEmpty()) : ?>
<div class="error">Ваша корзина пуста</div>
<?php else : ?>

    <h3 class="order-nr" style="text-align: center; margin-top: 1em; color: #ffffff;">Номер заказа: <b>№<?php echo $order;?></b> создан</h3>

    <label class="line"></label>

    <h4 style="text-align: center; margin-top: 1em; color: #ffffff;">Спасибо за оформление заказа, наши менеджеры свяжутся с вами.</h4>

</div>

<?php endif; ?>

</div>

<?php

Yii::$app->cart->removeAll();
Yii::$app->session->set('order', null);
Yii::$app->session->set('order_key',  null);

?>