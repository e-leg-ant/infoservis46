<?php

namespace backend\controllers;

use Yii;
use common\models\Brand;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Imagine\Image;
use yii\filters\AccessControl;

/**
 * BrandController implements the CRUD actions for Brand model.
 */
class BrandController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all Brand models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Brand::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Brand model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Brand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Brand(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post())) {

            $this->saveModel($model);

        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Brand model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $this->saveModel($model);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Brand model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Brand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Brand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Brand::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function createDirectory($path) {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }

    protected function saveModel(&$model) {
        $file = UploadedFile::getInstance($model, 'file');

        if ($file && $file->tempName) {
            $path = '/storage/brands/';

            $dir = Yii::getAlias('@frontend/web' . $path);

            $this->createDirectory($dir);

            // ???????????? ?????????????????????? ?????????? ?????? ???????????? ??????????
            $fileName = 'brand_' . date('U') . '.' . $file->extension;
            $file->saveAs($dir . $fileName);

            $model->file = $fileName;
            $model->image = $path . $fileName;
        }

        // ???????? ???????????? ???????????? ????????????????????,
        // ?????????? ?????????????????? ???????????? ???? ?????????????? ?????????????????????? ????????
        if ($model->id) {
            $currentModel = Brand::findOne($model->id);
        } else {
            $currentModel = null;
        }

        if ($model->save()) {

            // ???????? ?????? ???????????? ???????????? ???????????????????? ??????????????????????,
            // ???????????? ?????????? ?????????????? ?????????? ???????????? ????????????

            if ($file && $file->tempName) {
                if ($currentModel) {

                    $prevModelImage = Yii::getAlias('@frontend/web') . $currentModel->image;

                    if (file_exists($prevModelImage)) {
                        unlink($prevModelImage);
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
}
