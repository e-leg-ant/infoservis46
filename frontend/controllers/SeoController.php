<?php
namespace frontend\controllers;
use common\models\Brand;
use common\models\Information;
use common\models\InformationCategory;
use common\models\Product;
use console\models\Tasks;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\Category;


class SeoController extends Controller
{
    //public $layout = 'xml';

    public function actionSitemap()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');

        Yii::$app->response->data = $this->renderPartial('/seo/sitemap/begin');
        Yii::$app->response->data .= $this->renderPartial('/seo/sitemap/static');
        Yii::$app->response->data .= $this->renderPartial('/seo/sitemap/brands', ['brands' => Brand::find()->where(['status'=> '1'])->orderBy(['sort' => 'ASC'])->all()]);

        Yii::$app->response->data .= $this->renderPartial('/seo/sitemap/categories', ['categories' =>  Category::find()->select(['id', 'name', 'slug'])->all()]);

        Yii::$app->response->data .= $this->renderPartial('/seo/sitemap/items', ['items' => Product::find()->all()]);
        Yii::$app->response->data .= $this->renderPartial('/seo/sitemap/articles', ['information_category' =>  InformationCategory::find()->all(), 'information' =>  Information::find()->all()]);
        Yii::$app->response->data .= $this->renderPartial('/seo/sitemap/end');

    }

    public function actionYml($part = 1)
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');

        //$taskModel = Tasks::findOne(['id' => Tasks::TASK_SYNCHRONIZER_AVAILABLE]);

        //$dependency_cur_row_pos = Yii::$app->cache->get('yml-dependency-cur-row-pos');

        if (0 >= $part) {
            $i = 1;
        } else {
            $i = (int)$part;
        }

        $limit = 10000;

        $categories = Category::find()->select(['id', 'name', 'slug'])->orderBy('name ASC')->all();

        $categories_names = [];
        $categories_names_trans = [];

        foreach ($categories as $category) {

            $categories_names[$category->id] = $category->name;
            $categories_names_trans[$category->id] = mb_strtolower($category->slug);

        }

        \Yii::$app->response->data = $this->renderPartial('/seo/yml/begin', ['categories' => $categories]);

        $items = Product::find()->with(['values'])->where(['in', 'status', [1]])->limit($limit)->offset($limit * ($i - 1))->all();

        foreach ($items as $item) {
            \Yii::$app->response->data .= $this->renderPartial('/seo/yml/offer', [
                'item' => $item,
                'categoriesNames' => $categories_names,
                'categoriesNamesTrans' => $categories_names_trans,
            ]);
        }

        \Yii::$app->response->data .= $this->renderPartial('/seo/yml/end');
    }
}
