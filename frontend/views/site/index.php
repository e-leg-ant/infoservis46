<?php

use common\widgets\Brands\BrandsWidget;
use frontend\widgets\CategoriesList\CategoriesList;
use frontend\models\SelectionForm;
use frontend\models\TechnicalServiceForm;
use frontend\widgets\InformationList\InformationList;
use common\models\Settings;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Dialog;
use yii\web\View;

/* @var $this yii\web\View */

$this->title = $title;
$this->params['IsMain'] = true;

$text = Settings::get('config_main_text');

?>

<div class="wrap">

    <div class="ban">
        <img src="/images/ban.jpg"><div class="clear"></div>
    </div>

    <div class="wysiwyg"><?= $text; ?></div>

    <?= \Yii::$app->view->renderFile('@frontend/views/forms/callback.php'); ?>

</div>

<?php

$initIndex = <<< JS

JS;

$this->registerJs($initIndex, View::POS_READY, 'initIndex');

?>


