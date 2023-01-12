<?php

namespace backend\controllers;

use common\models\Information;
use Yii;
use common\models\InformationCategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use Imagine\Image;

/**
 * InformationController implements the CRUD actions for InformationCategory model.
 */
class InformationCategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => InformationCategory::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new InformationCategory();

        if ($model->load(Yii::$app->request->post())) {

            $this->saveModel($model);

        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
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
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InformationCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel(&$model) {

        $file = UploadedFile::getInstance($model, 'file');

        if ($file && $file->tempName) {

            $path = '/storage/information/categories/';

            $dir = Yii::getAlias('@frontend/web' . $path);

            $this->createDirectory($dir);

            // Запись уникального имени для нового файла
            $fileName = 'category_' . date('U') . '.' . $file->extension;
            $file->saveAs($dir . $fileName);

            $model->file = $fileName;
            $model->image = $path . $fileName;

        }

        // Если данная модель существует,
        // будет получение ссылки на текущий сохранненый файл
        if ($model->id) {
            $currentModel = InformationCategory::findOne($model->id);
        } else {
            $currentModel = null;
        }

        if ($model->save()) {

            // Если для данной модели существует изображение,
            // старое будет удалено после записи нового

            if ($file && $file->tempName) {

                if ($currentModel) {

                    $prevModelImage = Yii::getAlias('@frontend/web') . $currentModel->image;

                    if (!empty($currentModel->image) && file_exists($prevModelImage)) {
                        unlink($prevModelImage);
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    protected function createDirectory($path) {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }
}
