<?php
use yii\helpers\Url;
use app\models\Order;
?>
<?php if (!Yii::$app->cart->getIsEmpty()) : ?>
<a class="header__basket-quantity" href="<?= Url::to(['cart/index']) ?>"><b><?= Yii::$app->cart->getCount(); ?></b></a>
<?php else: ?>
<a class="header__basket-empty" href="<?= Url::to(['cart/index']); ?>"><span style="line-height: 20px;">0</span></a>
<?php endif; ?>
