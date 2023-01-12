<?php
use yii\web\View;
use yii\widgets\MaskedInput;
use yii\bootstrap\Button;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\file\FileInput;
use app\models\Order;

$title = 'Оформление заказа';
$this->title = $title;
$this->params['breadcrumbs'] = [$title];

?>

<div class="cart__order">

    <h3><?php echo $title; ?></h3>

    <label class="line"></label>

    <?php if (Yii::$app->cart->getIsEmpty()) : ?>
    <div class="error">Ваша корзина пуста</div>
    <?php else : ?>

    <div class="step-header-info">
        <div class="step-header-info-title">Уважаемые покупатели!</div>
        <div class="step-header-info-content">Вся предоставляемая информация является конфиденциальной и используется только для контактов Интернет-магазина с покупателем с целью
            согласования условий выполнения заказа и предоставления услуги.</div>
    </div>

        <?= Html::beginForm(['/cart/order', 'step' => '3'], 'post', ['class' => 'basket__order-form form-horizontal', 'role' => 'form', 'id' => 'step_2_form', 'enctype' => 'multipart/form-data']) ?>


    <div class="step-delivery-person-info step-delivery-person-info-individual reg-info">

        <div class="form-group form-full">
            <?php echo Html::label('Фамилия', 'profile_lastname', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo Html::textInput(
                'profile[lastname]',
                (!empty($data['lastname']) ? $data['lastname'] : ''),
                [
                    'id' => 'profile__last-name',
                    'maxlength' => 50,
                    'class' => 'form-control required',
                    'title' => 'Фамилия',
                    'onclick' => 'js: { $(this).removeClass("error"); }',
                    'onblur' => 'js: { if ("" == this.value) { $(this).addClass("error"); } }'
                ]
            ); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Html::label('Имя', 'profile_firstname', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo Html::textInput(
                'profile[firstname]',
                (!empty($data['firstname']) ? $data['firstname'] : ''),
                [
                    'id' => 'profile__first-name',
                    'maxlength' => 50,
                    'class' => 'form-control  required',
                    'title' => 'Имя',
                    'onclick' => 'js: { $(this).removeClass("error"); }',
                    'onblur' => 'js: { if ("" == this.value) { $(this).addClass("error"); } }'
                ]
            ); ?>
            </div>
        </div>

        <div class="form-group form-full">
            <?php echo Html::label('Отчество', 'profile_fathersname', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo Html::textInput(
                'profile[fathersname]',
                (!empty($data['fathersname']) ? $data['fathersname'] : ''),
                [
                    'id' => 'profile__fathers-name',
                    'maxlength' => 50,
                    'class' => 'form-control ',
                    'title' => 'Отчество'
                ]
            ); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Html::label('Мобильный, 10 знаков, <span style="color:red;">без +7 или 8</span>', 'profile_mobile_phone', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo MaskedInput::widget([
                'name' => 'profile[mobile_code]',
                'value' => (!empty($data['mobile_code']) ? $data['mobile_code'] : ''),
                'options' => [
                    'type' => 'tel',
                    'id' => 'profile__mobile-code',
                    'class' => 'form-control required mobile-short-phone-code-mask',
                    'style' => 'width:60px; display: inline-block;',
                    'placeholder' => '___',
                    'title' => 'Код',
                    'onclick' => 'js: { $(this).removeClass("error"); }',
                    'onkeyup' => 'js: {  var reg_phone_code = /^[0-9]{3}$/; if ( reg_phone_code.test($("#profile__mobile-code").val()) ) { $("#profile__mobile-phone").focus().trigger("click"); } }'
                ],
                'mask' => '999',
            ]); ?>
                <?php echo MaskedInput::widget([
                'name' => 'profile[mobile_phone]',
                'value' => (!empty($data['mobile_phone']) ? $data['mobile_phone'] : ''),
                'options' => [
                    'type' => 'tel',
                    'id' => 'profile__mobile-phone',
                    'class' => 'form-control required mobile-short-phone-code-mask',
                    'style' => 'width:130px; display: inline-block;',
                    'placeholder' => '___-__-__',
                    'title' => 'Номер',
                    'onclick' => 'js: { $(this).removeClass("error"); }',
                    'onblur' => 'js: { if ("" == this.value || 9 != this.value.length) { $(this).addClass("error"); } }'
                ],
                'mask' => '999-99-99',
            ]); ?>
            </div>
        </div>

        <div class="form-group form-full">
            <?php echo Html::label('Дополнительный тел.', 'profile_phone', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo Html::textInput(
                'profile[phone]',
                (!empty($data['phone']) ? $data['phone'] : ''),
                [
                    'id' => 'profile__phone',
                    'maxlength' => 50,
                    'class' => 'form-control',
                    'placeholder' => 'Желательно указать',
                    'title' => 'Желательно указать'
                ]); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Html::label('E-mail', 'profile_email', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo Html::textInput(
                'profile[email]',
                (!empty($data['email']) ? $data['email'] : ''),
                [
                    'id' => 'profile__email',
                    'maxlength' => 50,
                    'title' => 'E-mail',
                    'class' => 'form-control '
                ]
            ); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Html::label('Название организации', 'profile_organization', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo Html::textInput(
                    'profile[organization]',
                    (!empty($data['organization']) ? $data['organization'] : ''),
                    [
                        'id' => 'profile__organization',
                        'maxlength' => 100,
                        'class' => 'form-control',
                        'title' => 'Название организации',
                        'onclick' => 'js: { $(this).removeClass("error"); }',
                    ]
                ); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Html::label('ИНН', 'profile_inn', ['class'=> 'col-lg-4 control-label']); ?>
            <div class="col-lg-5">
                <?php echo Html::textInput(
                    'profile[inn]',
                    (!empty($data['inn']) ? $data['inn'] : ''),
                    [
                        'id' => 'profile__inn',
                        'maxlength' => 100,
                        'class' => 'form-control',
                        'title' => 'ИНН',
                        'onclick' => 'js: { $(this).removeClass("error"); }',
                    ]
                ); ?>
            </div>
        </div>

    </div>

    <div class="basket__steps">

        <div class="basket__steps-left">

            <?= Button::widget([
                'label' => 'Назад',
                'options' => [
                    'class' => 'main__button main__button_grey',
                    'href' => Url::to(['cart/index']),
                ],
                'tagName' => 'a'
            ]); ?>

        </div>

        <div class="basket__steps-right">

            <?= Button::widget([
                'label' => 'ВПЕРЕД',
                'options' => [
                    'class' => 'main__button main__button_red basket__steps-confirm-button',
                    'href' => Url::to(['cart/end']),
                    'style' => 'float:right; font-weight:bold;',
                    'data-method' => 'post'
                ],
                'tagName' => 'a'
            ]); ?>

        </div>

    </div>

    <div class="basket__steps-remarks">
        <div>
            <span style="color:red; font-weight: bold;">*</span>
            <span style="color: #ffffff;"> Поля, обязательные для заполнения</span>
        </div>
        <div class="step-ems-max-weight-info"></div>
        <div id="flash-messages-container"><div class="alert alert-danger" style="display:none;"></div></div>
    </div>

        <?= Html::endForm() ?>

    <?php endif; ?>

</div>

<?php
$initOrderStep2Page = <<< JS


    $('.basket__steps-confirm-button').click(function () {

        var reg_phone_code = /^[0-9]{3}$/;
        var reg_phone_number = /^[0-9]{3}-[0-9]{2}-[0-9]{2}$/;

        if ('' == $('#profile__last-name').val() && $('#profile__last-name').is(':visible')) {
            $('#flash-messages-container > .alert-danger').html('Не указана фамилия').fadeIn().animate({opacity: 1.0}, 5000).fadeOut('slow');
            $('#profile__last-name').addClass('error');
            return false;
        } else if ('' == $('#profile__first-name').val()) {
            $('#flash-messages-container > .alert-danger').html('Не указано имя').fadeIn().animate({opacity: 1.0}, 5000).fadeOut('slow');
            $('#profile__first-name').addClass('error');
            return false;
        }  else if (!reg_phone_code.test($('#profile__mobile-code').val()) || !reg_phone_number.test($('#profile__mobile-phone').val()) || $('#profile__mobile-code').val().substr(0,1) == 8 || $('#profile__mobile-code').val().substr(0,1) == '7') {
            $('#flash-messages-container > .alert-danger').html('Не верный формат мобильного номера').fadeIn().animate({opacity: 1.0}, 5000).fadeOut('slow');
            $('#profile__mobile-code').addClass('error');
            $('#profile__mobile-phone').addClass('error');
            return false;
        } else if ('' == $('#profile__email').val() && $('#profile__email').hasClass('required')) {
            $('#flash-messages-container > .alert-danger').html('Не указан E-mail').fadeIn().animate({opacity: 1.0}, 5000).fadeOut('slow');
            $('#profile__email').addClass('error');
            return false;
        } else if ('' == $('#profile__passport').val() && $('#profile__passport').hasClass('required') && $('#profile__passport-type').is(':visible')) {
            $('#flash-messages-container > .alert-danger').html('Не указан паспорт или в/у').fadeIn().animate({opacity: 1.0}, 5000).fadeOut('slow');
            $('#profile__passport').addClass('error');
            return false;
        }  else if ('' != $('#profile__passport').val() && '' == $('#profile__passport-type').val() && $('#profile__passport-type').is(':visible')) {
            $('#flash-messages-container > .alert-danger').html('Не выран тип удостоверения личности').fadeIn().animate({opacity: 1.0}, 5000).fadeOut('slow');
            $('#profile__passport-type').addClass('error');
            return false;
        } else {
            var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if (!pattern.test($('#profile__email').val()) && $('#profile__email').hasClass('required')){
                $('#flash-messages-container > .alert-danger').html('Не верно указан E-mail').fadeIn().animate({opacity: 1.0}, 5000).fadeOut('slow');
                $('#profile__email').addClass('error');
                return false;
            }
        }

        $('#step_2_form').attr('action', '/cart/end');
        
        $('#step_2_form').submit();

        });

JS;

$this->registerJs($initOrderStep2Page, View::POS_READY, 'initOrderStep2Page');
