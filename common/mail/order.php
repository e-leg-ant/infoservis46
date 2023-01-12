<?php

use common\models\Settings;
use yii\helpers\Html;
use app\models\SparesModel;
use app\models\Order;
use app\models\CatalogItem;

$phone = Settings::get('config_telephone');
$email = Settings::get('config_email');
?>

<style>
.order-p-table table {
	width:100%;
	}

	.order-p-table table tr {
		background:#e0f2f5;
		margin-bottom:7px;
		width:100%;
		border:#e0f2f5 1px solid;
		float:left;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		}

	.order-p-table table .tr2 {
		background:#f4f5e0;
		border:#f4f5e0 1px solid;
		}

		.order-p-table table tr td {
			float:left;
			height:28px;
			line-height:28px;
			vertical-align:middle;
			font-size:12px;
			color:#3d3e40;
			}

		.order-p-table table tr .td1 {
			width:375px;
			padding-left:10px;
			}

		.order-p-table table tr .td2 {
			width:60px;
			}

			.order-p-table table tr .td2 input {
				padding:2px 0;
				text-align:center;
				font-size:12px;
				height:13px;
				width:40px;
				border:#dfdfdf 1px solid;
				}

		.order-p-table table tr .td3 {
			width:75px;
            text-align:right;
            padding-right: 10px;
			}

		.order-p-table table tr .td4 {
			font-weight:700;
			width:65px;
            text-align:right;
            padding-right: 10px;
			}

		.order-p-table table tr .td5 a {
			color:#016978;
			font-size:13px;
			font-family:'MyriadPro';
            text-align:right;
			}
h1 {
    color: #016978;
    font-size: 18px;
    font-weight: 500;
    line-height: 25px;
    text-decoration: none;
}
h2 {
    color: #016978;
    font-size: 14px;
    font-weight: 500;
    line-height: 25px;
    text-decoration: none;
}
</style>

<div>
    <h1>Спасибо. Ваш заказ принят. Ему присвоен номер №<?php echo $orderNumber;?><br /><br />
        Информация о заказе:</h1>

    <div class="order-p-table">

        <table>

            <tr>
                <td class="td1"><b>Наименование</b></td>
                <td class="td2"><b>Количество</b></td>
            </tr>

            <?php $r = 0; $total = 0; ?>
            <?php foreach ($orderContents['items'] as $item) : ?>

                <?php $total += $item['amount']; ?>

                <tr<?php echo ($r++ % 2 ? ' class="tr2"' : ''); ?>>
                    <td class="td1"><?php echo Html::encode($item['name']); ?></td>
                    <td class="td2"><?php echo $item['price']; ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

    </div>

    <br/><br/>

    <div>
        <h2>Интернет магазин </h2>
        <b>Заказы</b><br />
        Тел.: <?= $phone; ?><br />

        <b>e-mail:</b><?= $email; ?><br/><br/>
    </div>

</div>

<div class="foot-note">
    ---
    <br />
    Это письмо было сгенерировано автоматически. Пожалуйста, не отвечайте на него.
</div>
