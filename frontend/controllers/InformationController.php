<?php
namespace frontend\controllers;

use backend\widgets\AdminGallery\Image;
use common\models\Information;
use common\models\InformationCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\Category;
use yii\data\Pagination;
use Yii;

/**
 * Information controller
 */
class InformationController extends Controller
{

    public function actionIndex($category = null, $slug = null)
    {

        if (empty($category)) {

            $categoryModels = InformationCategory::find()->all();

            Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'Информация для клиентов по проектированию холодильных камер, предоставляемых услуг монтажа и ремонта холодильного оборудования.']);

            return $this->render('categories', [
                'categories' => $categoryModels
            ]);

        } elseif (!empty($slug)) {

            $information = Information::find()->where(['slug' => $slug])->one();

            if (empty($information)) {
                throw new \yii\web\HttpException('404', 'Страница не существует');
                return;
            }

            $categoryModel = InformationCategory::find()->where(['slug' => $category])->one();

            if (!empty($categoryModel)) {

                $breadcrumbs = [];

                if (!empty($categoryModel->name)) {

                    $breadcrumbs[] = [
                        'label' => Html::encode($categoryModel->name),
                        'url' => ['information/category', 'slug' => $categoryModel->slug]
                    ];

                }

                Yii::$app->view->registerLinkTag(['rel' => 'canonical', 'href' => Url::to(['information/index', 'category' => $categoryModel->slug, 'slug' => $information->slug], true)]);

                $breadcrumbs[] = $information->name;

                Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $information->meta_description]);

                $images = Image::find()->where(['item_id' => 1])->limit(6)->all();

                $data['images'] = [];

                foreach ($images as $image) {

                    $image->extension = 'jpg';

                    $data['images'][] = [
                        'src' => Yii::getAlias('@web/storage/gallery') . $image->getUrl('original'),
                        'preview_src' => Yii::getAlias('@web/storage/gallery') . $image->getUrl('preview'),
                        'title' => $image->alt,
                        'alt' => $image->alt,
                    ];
                }

                return $this->render('index', [
                    'information' => $information,
                    'breadcrumbs' => $breadcrumbs,
                    'category' => $categoryModel,
                    'images' => $data['images']
                ]);

            } else {
                throw new \yii\web\HttpException('404', 'Страница не существует');
                return;
            }

        } else {
            throw new \yii\web\HttpException('404', 'Страница не существует');
            return;
        }

    }

    public function actionCategory($slug = null)
    {

        if (!empty($slug)) {

            // Поиск категории по slug, иначе ошибка

            $category = InformationCategory::find()->where(['slug' => $slug])->one();

            /**
             * Возвращаем 404 ошибку, если товар не существует или неактивен
             */
            if (empty($category)) {
                throw new \yii\web\HttpException('404', 'Страница не существует');
                return;
            }

            $information = Information::find()->where(['category_id' => $category->id])->all();

            $breadcrumbs = [];

            if (!empty($category->name)) {

                $breadcrumbs[] = [
                    'label' => Html::encode($category->name),
                    'url' => ['information/category', 'slug' => $category->slug]
                ];

            }

            Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $category->meta_description]);

            return $this->render('category', [
                'category' => $category,
                'information' => $information,
                'breadcrumbs' => $breadcrumbs,
            ]);

        }  else {

            $categoryModels = InformationCategory::find()->all();

            return $this->render('categories', [
                'categories' => $categoryModels
            ]);

        }
    }
}
