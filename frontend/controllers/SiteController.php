<?php
namespace frontend\controllers;

use backend\widgets\AdminGallery\Image;
use common\models\Brand;
use common\models\GalleryImages;
use frontend\models\CallBackForm;
use frontend\models\TechnicalServiceForm;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\SelectionForm;
use frontend\models\InviteMeasurerForm;
use common\models\Settings;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $data_settings = Settings::find()->where(['key' => 'config_main_seo_title'])->one();
        $title = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_main_seo_description'])->one();
        $description = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_main_text'])->one();
        $text = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';


        Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $description]);

        return $this->render('index', [
            'title' => $title,
            'text' => $text,
        ]);
    }

    /**
     * Displays контакты.
     *
     * @return mixed
     */
    public function actionContacts()
    {
        Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'Контактная информация.']);

        $data = [];

        $data['contact_name'] = Settings::get('config_contact_name');
        $data['address'] = Settings::get('config_address');
        $data['telephone'] = Settings::get('config_telephone');
        $data['telephone2'] = Settings::get('config_fax');
        $data['contact_email'] = Settings::get('config_email');
        $data['contact_map_code'] = html_entity_decode(Settings::get('contact_map_code'), ENT_QUOTES);

        $data['contact_welcome'] = html_entity_decode(Settings::get('config_contact_welcome'));

        return $this->render('contacts', $data);
    }

    /**
     *
     *
     * @return mixed
     */
    public function actionCallback()
    {
        $model = new CallBackForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Сообщение отправлено, мы как можно скорее свяжемся с вами.');
            } else {
                Yii::$app->session->setFlash('error', 'Сообщение не было отправлено.');
            }

        }

        return (Yii::$app->request->referrer) ? $this->redirect(Yii::$app->request->referrer) : $this->goBack();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function beforeAction($action)
    {

        $absoluteUrl = Yii::$app->request->absoluteUrl;

        preg_match('~\.*site/index*~', $absoluteUrl, $matches);

        if (!empty($matches) || 'site' == str_replace('/', '', Yii::$app->request->getUrl())) {
            return Yii::$app->response->redirect('/', 301);
        }

        return parent::beforeAction($action);
    }
}
