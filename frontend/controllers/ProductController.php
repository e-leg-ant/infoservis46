<?php
namespace frontend\controllers;

use common\models\Category;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use common\models\Product;

/**
 * Product controller
 */
class ProductController extends Controller
{

    public function actionIndex($category = null, $slug = null)
    {

        // Поиск товара по slug, иначе ошибка

        $breadcrumbs = [];

        $breadcrumbs[] = [
            'label' => Html::encode('Каталог продукции'),
            'url' => ['category/index']
        ];

        $product = Product::find()->where(['slug' => $slug])->one();

        /**
         * Возвращаем 404 ошибку, если товар не существует или неактивен
         */
        if (empty($product)) {
            throw new \yii\web\HttpException('404', 'Товар не существует');
            return;
        }

        if (!empty($product->meta_description)) {
            Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $product->meta_description]);
        }

        if (!empty($product->categoryLink->category_id)) {

            $category = Category::find()->where(['id' => $product->categoryLink->category_id])->one();

            $breadcrumbs[] = [
                'label' => Html::encode($category->name),
                'url' => ['category/index', 'slug' => $category->slug]
            ];
        } else {
            $category = null;
        }

        $breadcrumbs[] = $product->name;

        return $this->render('index', [
            'product' => $product,
            'category' => $category,
            'breadcrumbs' => $breadcrumbs
        ]);

    }
}
