<?php
namespace frontend\controllers;

use common\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\Category;
use yii\data\Pagination;
use Yii;

/**
 * Category controller
 */
class CategoryController extends Controller
{

    public function actionIndex($slug = null)
    {

        $breadcrumbs = [];

        $breadcrumbs[] = [
            'label' => Html::encode('Каталог продукции'),
            'url' => ['category/index']
        ];

        // Поиск категории по slug, иначе ошибка
        if (!empty($slug)) {

            $category = Category::find()->where(['slug' => $slug])->with('parent')->one();

            /**
             * Возвращаем 404 ошибку, если товар не существует или неактивен
             */
            if (empty($category)) {
                throw new \yii\web\HttpException('404', 'Категория не существует');
                return;
            }

            if (!empty($category->meta_description)) {
                Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $category->meta_description]);
            }

            if (!empty($category->parent)) {

                $breadcrumbs[] = [
                    'label' => Html::encode($category->parent->name),
                    'url' => ['category/index', 'slug' => $category->parent->slug]
                ];

            }

            $breadcrumbs[] = $category->name;

            // Получение запроса товаров категории
            $query = $category->getAllProducts('query');

        } else {

            $category = new Category();

            // Получение запроса товаров категории
            $query = Product::find()->with('productImages')->orderBy('status desc');
        }


        // Получение параметров из get-запроса, если нужно применить фильтрацию
        $get = Yii::$app->request->get();

        // Получение списка параметров для фильтрации по свойствам товаров
        $discountsWhere = [];
        $typesWhere = [];
        $brandsWhere = [];

        foreach ($get as $key => $value) {
            if (strpos($key, "discount") !== false) {
                $discountsWhere[] = $value;
            } elseif (strpos($key, "type") !== false) {
                $typesWhere[] = $value;
            } elseif (strpos($key, "brand") !== false) {
                $brandsWhere[] = $value;
            }
        }

        // Фильтруем товары согласно свойствам
        if ($discountsWhere) {
            $query->andWhere(['discount_id' => $discountsWhere]);
        }

        if ($brandsWhere) {
            $query->andWhere(['brand_id' => $brandsWhere]);
        }

        //$query->andWhere('price > 0');

        $query->andWhere(['status' => '1']);

        // Настройки пагинации
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);

        $products = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $products_html = '';

        foreach ($products as $product) {

            if (!empty($product->categoryLink->category_id)) {
                $cardUrl = Url::to(['product/index', 'category' => $product->categoryLink->category->slug, 'slug' => $product->slug]);
            } else {
                $cardUrl = Url::to(['product/index', 'slug' => $product->slug]);
            }

            $products_html .= $this->renderPartial('/product/card/large', ['product' => $product, 'url' => $cardUrl, 'category' => $product->categoryLink->category], true, false);
        }

        return $this->render('index', [
            'category' => $category,
            'products' => $products_html,
            'pages' => $pages,
            'discountsWhere' => $discountsWhere,
            'brandsWhere' => $brandsWhere,
            'breadcrumbs' => $breadcrumbs
        ]);


    }
}
