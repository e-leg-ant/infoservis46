<?php
namespace backend\controllers;

use common\models\Category;
use common\models\ContentCategoryDescription;
use Yii;

use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Settings;

/**
 * Site controller
 */
class SettingsController extends Controller
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
                        'actions' => ['updatebykey', 'mainpage', 'upload', 'imagesget', 'filedelete', 'contacts', 'counters', 'seo'],
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
            'upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => Yii::$app->urlManagerFrontend->baseUrl . '/storage/images/', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@frontend/web/storage/images/'), // Or absolute path to directory where files are stored.
                'uploadOnlyImage' => true, // For any kind of files uploading.
            ],
            'imagesget' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' =>  Yii::$app->urlManagerFrontend->baseUrl . '/storage/images/', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@frontend/web/storage/images/'), // Or absolute path to directory where files are stored.
                'options' => [
                    'only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.ico'],
                    'basePath' => Yii::getAlias('@frontend/web/storage/images/'),
                    'recursive' => false
                ], // These options are by default.
            ],
            'filedelete' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' =>  Yii::$app->urlManagerFrontend->baseUrl . '/storage/images/', // Directory URL address, where files are stored.
                'path' => Yii::getAlias('@frontend/web/storage/images/'), // Or absolute path to directory where files are stored.
            ],
        ];
    }



    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionContacts()
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

        $data_settings = Settings::find()->where(['key' => 'config_contact_name'])->one();
        $data['config_contact_name'] = (!empty($data_settings->value)) ? $data_settings->value : '';

        $data_settings = Settings::find()->where(['key' => 'config_address'])->one();
        $data['config_address'] = (!empty($data_settings->value)) ? $data_settings->value : '';

        $data_settings = Settings::find()->where(['key' => 'config_telephone'])->one();
        $data['config_telephone'] = (!empty($data_settings->value)) ? $data_settings->value : '';

        $data_settings = Settings::find()->where(['key' => 'config_fax'])->one();
        $data['config_fax'] = (!empty($data_settings->value)) ? $data_settings->value : '';

        $data_settings = Settings::find()->where(['key' => 'config_telephone3'])->one();
        $data['config_telephone3'] = (!empty($data_settings->value)) ? $data_settings->value : '';

        $data_settings = Settings::find()->where(['key' => 'config_email'])->one();
        $data['config_email'] = (!empty($data_settings->value)) ? $data_settings->value : '';

        $data_settings = Settings::find()->where(['key' => 'contact_map_code'])->one();
        $data['contact_map_code'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_contact_welcome'])->one();
        $data['config_contact_welcome'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        return $this->render('contacts', [
            'data' => $data,
        ]);
    }

    /**
     *
     * @return mixed
     */
    public function actionCounters()
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

        $data_settings = Settings::find()->where(['key' => 'config_counter_yandex'])->one();
        $data['config_counter_yandex'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_counter_google'])->one();
        $data['config_counter_google'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        return $this->render('counters', [
            'data' => $data,
        ]);
    }

    /**
     *
     * @return mixed
     */
    public function actionSeo()
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

        $data_settings = Settings::find()->where(['key' => 'config_seo_category_title'])->one();
        $data['config_seo_category_title'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_seo_category_description'])->one();
        $data['config_seo_category_description'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_seo_category_h1'])->one();
        $data['config_seo_category_h1'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_seo_product_title'])->one();
        $data['config_seo_product_title'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_seo_product_description'])->one();
        $data['config_seo_product_description'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        $data_settings = Settings::find()->where(['key' => 'config_seo_product_h1'])->one();
        $data['config_seo_product_h1'] = (!empty($data_settings->value)) ? html_entity_decode($data_settings->value, ENT_QUOTES) : '';

        return $this->render('seo', [
            'data' => $data,
        ]);
    }

    public function actionUpdatebykey($key, $value)
    { 
        $model = Settings::find()->where(['key' => $key])->one();

        if ($model) {

            $model->value = $value;

            $model->save(false);

        }
    }

}
