<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\models\CallBackForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;
use yii\jui\Dialog;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use common\models\Settings;

$modelCAllBackForm = new CallBackForm();

$counter_yandex_setting = Settings::find()->where(['key' => 'config_counter_yandex'])->one();
$counter_yandex = (!empty($counter_yandex_setting->value)) ? html_entity_decode($counter_yandex_setting->value, ENT_QUOTES) : '';
$counter_google_setting = Settings::find()->where(['key' => 'config_counter_google'])->one();
$counter_google = (!empty($counter_google_setting->value)) ? html_entity_decode($counter_google_setting->value, ENT_QUOTES) : '';

AppAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?= $counter_google; ?>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon" sizes="16x16">
</head>
<body>

<?php $this->beginBody() ?>

<?php echo \Yii::$app->view->renderFile('@frontend/views/common/header.php'); ?>

<div class="root">

    <div class="wrap">

        <div class="cnntbl">

            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'На главную' , 'url' => Yii::$app->homeUrl],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
            ]); ?>

            <?php
            foreach(Yii::$app->session->getAllFlashes() as $key => $message) {
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-' . $key
                    ],
                    'body' => $message
                ]);
            } ?>


            <?= $content; ?>

        </div>

    </div>

</div>


<?php echo \Yii::$app->view->renderFile('@frontend/views/common/footer.php'); ?>

<?php
$initBasketTotal = <<< JS
    $.ajax({
        url: '/cart/totals',
        success: function(data) {
                $('.header__basket').html(data);
        }
    });
JS;

$this->registerJs($initBasketTotal, View::POS_READY, 'initBasketTotal');

$initBuyBtn = <<< JS
    $('body').on('click', '.buy-btn', function () {

        var owner = $(this);
        
        var quantity = $('.qty-count .product-quantity').val();

        if (null === quantity) {
            quantity = 1;
        }

        var id = $(this).attr('data-item-id');
        
        $.ajax({
            url: this.href,
            data: { quantity: quantity },
            success: function(data) {
                owner.clone().css({'position' : 'absolute', 'z-index' : '1000', top: owner.offset().top, left:owner.offset().left})
                     .appendTo("body")
                     .animate({opacity: 0.05,
                            left: $('.header__basket').offset()['left'],
                            top: $('.header__basket').offset()['top'],
                            width: 40}, 1000, function() {
                                $(this).remove();
                            });
                
                $.ajax({ url: '/cart/totals', success: function(data) { $('.header__basket').html(data); } });

            },
            error: function(data) {
                alert('Не удалось добавить товар в корзину')
            }
        });

        return false;
    });
JS;

$this->registerJs($initBuyBtn, View::POS_READY, 'initBuyBtn');

$initMain = <<< JS

    $('.dialog_call_back_btn').click(function () {
        $('#modal__call_back').dialog('open');
        return false;
    });

JS;

$this->registerJs($initMain, View::POS_READY, 'initMain');

?>

<?php $this->endBody() ?>

<!--//menu-->
<!----->
<!---//End-rate---->

<?= $counter_yandex; ?>

</body>
</html>
<?php $this->endPage() ?>
