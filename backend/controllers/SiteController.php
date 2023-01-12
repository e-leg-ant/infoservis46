<?php
namespace backend\controllers;

use common\models\Settings;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Tasks;
use yii\data\ActiveDataProvider;

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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $data_post = Yii::$app->request->post('data');

        if (!empty($data_post) && is_array($data_post)) {

            foreach ($data_post as $key => $row) {

                $modelSetting = Settings::find()->where(['key' =>  $key])->one();

                if (!$modelSetting) {
                    $modelSetting = new Settings();
                }

                $modelSetting->group = 'config';
                $modelSetting->key = $key;

                $modelSetting->value = $row;

                $modelSetting->save(false);
            }
        }

        $data = [];

        $data_settings = Settings::find()->where(['key' => 'config_main_seo_title'])->one();
        $data['config_main_seo_title'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_main_seo_description'])->one();
        $data['config_main_seo_description'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_main_text'])->one();
        $data['config_main_text'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_about_text'])->one();
        $data['config_about_text'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';


        return $this->render('index', ['data' => $data]);
    }

    /**
     * Login action.
     *
     * @return string
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
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
