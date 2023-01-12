<?php

use common\models\Settings;
use frontend\widgets\Contacts\Contacts;
use yii\helpers\Url;

$modelCategoryInformation = \common\models\InformationCategory::find()->where(['id' => 1])-> one();

?>

<div class="footer">

    <div class="fotcnt">

        <div class="fmenu">
            <ul class="menu_footer">
                <li>
                    <a href="<?= Url::home(); ?>">Главная</a>
                </li>
                <li>
                    <a href="<?= Url::to( ['information/category', 'slug' => $modelCategoryInformation->slug]); ?>">Каталог</a>
                </li>
                <li>
                    <a href="<?= Url::to(['site/contacts']); ?>">Контакты</a>
                </li>
            </ul>
        </div>

        <div class="devcopy"></div>
    </div>

</div>
