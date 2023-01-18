<?php

use frontend\models\CallBackForm;
use yii\bootstrap\ActiveForm;
use common\models\Settings;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;

$modelCallBackForm = new CallBackForm();

$phone = Settings::get('config_telephone');
$phone2 = Settings::get('config_fax');
$phone3 = Settings::get('config_telephone3');
$email = Settings::get('config_email');
$email = Settings::get('config_email');
$address = Settings::get('config_address');
?>

<div class="callback">

    <h2>Обратная связь</h2>

    <div class="_form">

        <div class="formcnt">

            <?php $formCallBackForm = ActiveForm::begin(['id' => 'call-back-form', 'class' => 'feed_back_form', 'action' => Url::to(['site/callback'])]); ?>

            <?= $formCallBackForm->field($modelCallBackForm, 'name')->textInput(['placeholder' => true])->label(false); ?>

            <?= $formCallBackForm->field($modelCallBackForm, 'phone')->textInput(['placeholder' => true])->label(false); ?>

            <?= $formCallBackForm->field($modelCallBackForm, 'text')->textarea(['placeholder' => true])->label(false); ?>

            <?= $formCallBackForm->field($modelCallBackForm, 'file')->fileInput(['placeholder' => true, 'class' => 'custom-file-input'])->label(false); ?>

            <div>Нажимая кнопку «Отправить», я соглашаюсь на получение информации от интернет-магазина, а также принимаю <?= Html::a('условия политики конфиденциальности', Url::to(['/information/informacziya/politika-konfidenczialnosti']), ['class' => '', 'style' => 'text-decoration:underline;', 'target' => '_blank']); ?> и <?= Html::a('пользовательского соглашения', Url::to(['/information/informacziya/polzovatelskoe-soglashenie']), ['class' => '', 'style' => 'text-decoration:underline;', 'target' => '_blank']); ?>.</div>

            <input type="text" name="about" id="about" value="" class="text txthid">

            <?= $formCallBackForm->field($modelCallBackForm, 'verifyCode')->widget(Captcha::class, ['options' => ['placeholder'=> 'Введите текст с картинки']])->label(false); ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn button sbtbtn', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

        <div class="feedinfo">

            <p>Свяжитесь с нами удобным для вас способом или оставьте свои контактные даные.</p>

            <a href="tel:<?= $phone; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 19">
                    <path d="M14.228 11.163c-.369-.384-.814-.59-1.285-.59-.468 0-.917.202-1.3.586l-1.203 1.198c-.099-.053-.197-.103-.292-.152-.137-.069-.267-.133-.377-.202-1.126-.715-2.149-1.647-3.13-2.852-.476-.601-.795-1.107-1.027-1.62.312-.286.6-.582.882-.868.107-.106.213-.216.32-.323.799-.798.799-1.833 0-2.632L5.778 2.67c-.118-.118-.24-.24-.354-.361a17.96 17.96 0 00-.715-.708c-.37-.365-.81-.559-1.274-.559-.464 0-.913.194-1.293.56l-.008.007L.841 2.913a2.782 2.782 0 00-.825 1.769c-.092 1.11.235 2.145.486 2.822.617 1.662 1.537 3.202 2.91 4.853a17.898 17.898 0 005.96 4.666c.874.415 2.042.906 3.346.99.08.003.164.007.24.007.879 0 1.616-.316 2.194-.943.004-.008.012-.012.016-.02.198-.239.426-.456.665-.688.164-.156.331-.32.495-.49.376-.392.574-.848.574-1.316 0-.472-.202-.924-.586-1.305l-2.088-2.095zm1.362 4.005c-.004 0-.004.003 0 0-.148.16-.3.304-.464.464-.247.235-.498.482-.734.76-.384.41-.837.605-1.43.605-.057 0-.118 0-.175-.004-1.13-.072-2.18-.513-2.967-.89a16.894 16.894 0 01-5.613-4.396c-1.297-1.564-2.164-3.009-2.739-4.56-.353-.948-.483-1.685-.425-2.381.038-.445.209-.814.524-1.13L2.864 2.34c.187-.175.384-.27.578-.27.24 0 .434.145.556.266l.011.012c.232.217.453.441.685.68.118.122.24.244.36.37l1.04 1.038c.402.403.402.776 0 1.179-.111.11-.218.22-.328.327-.32.327-.624.631-.954.928-.008.007-.016.011-.02.019-.327.327-.266.646-.197.863l.011.034c.27.655.65 1.27 1.229 2.005l.003.004c1.05 1.293 2.157 2.3 3.378 3.072.156.1.315.18.467.255.137.069.267.133.377.202.015.008.03.019.046.027.129.064.25.095.376.095a.813.813 0 00.578-.263l1.3-1.3c.13-.13.336-.286.575-.286.236 0 .43.149.548.278l.008.008 2.095 2.095c.392.388.392.787.004 1.19zM9.725 4.286a4.823 4.823 0 012.625 1.362 4.849 4.849 0 011.361 2.624.51.51 0 00.506.426c.03 0 .057-.004.087-.007a.514.514 0 00.422-.594 5.865 5.865 0 00-1.646-3.175 5.866 5.866 0 00-3.176-1.647.516.516 0 00-.593.418.508.508 0 00.414.593zM17.986 7.949a9.65 9.65 0 00-2.712-5.23 9.65 9.65 0 00-5.23-2.711.512.512 0 10-.167 1.011 8.641 8.641 0 014.675 2.423 8.616 8.616 0 012.422 4.674.51.51 0 00.506.426c.03 0 .057-.004.088-.007a.504.504 0 00.418-.586z"></path>
                </svg>
                <span><?= $phone; ?></span>
            </a>

            <a href="mailto:<?= $email; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 19 15">
                    <path d="M16.413 0H2.286A2.288 2.288 0 000 2.286v9.655a2.288 2.288 0 002.286 2.286H16.41a2.288 2.288 0 002.286-2.286V2.29A2.286 2.286 0 0016.413 0zm1.241 11.941c0 .685-.556 1.242-1.241 1.242H2.286a1.243 1.243 0 01-1.242-1.242V2.29c0-.685.557-1.242 1.242-1.242H16.41c.685 0 1.242.557 1.242 1.242v9.651h.003z"></path>
                    <path d="M11.79 6.994l4.573-4.1a.525.525 0 00.038-.74.525.525 0 00-.739-.038l-6.305 5.66-1.23-1.1a.774.774 0 00-.093-.085L3.03 2.112a.522.522 0 00-.739.043.522.522 0 00.043.738L6.959 7.03l-4.607 4.313a.524.524 0 00.715.765l4.677-4.374 1.269 1.133a.522.522 0 00.696-.004l1.304-1.168 4.65 4.417a.522.522 0 10.72-.758l-4.593-4.36z"></path>
                </svg>
                <span><?= $email; ?></span>
            </a>
            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                    <g clip-path="url(#clip0)"><path d="M8.736 0C3.92 0 0 3.92 0 8.736c0 4.817 3.92 8.736 8.736 8.736 4.817 0 8.736-3.919 8.736-8.736S13.553 0 8.736 0zm0 16.38c-4.215 0-7.644-3.429-7.644-7.644s3.43-7.644 7.644-7.644c4.215 0 7.645 3.43 7.645 7.644 0 4.215-3.43 7.645-7.645 7.645z"></path><path d="M9.282 3.276H8.19v5.686l3.436 3.436.772-.772L9.282 8.51V3.276z"></path></g>
                    <defs><clipPath id="clip0"><path d="M0 0h17.473v17.473H0z"></path></clipPath></defs>
                </svg>
                <span>Пн - Пт c 10:00 по 19:00</span>
            </a>
            <a href=#">
                <span style="margin-left: 0px;"><?= $address; ?></span>
            </a>

        </div>

    </div>

</div>
