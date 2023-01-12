<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;
use linslin\yii2\curl;


use app\models\Settings;

class SearchController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
     *
	 */

	public function actionIndex($limit = 200, $auto = false)
	{

	    $query = trim(strip_tags(Yii::$app->request->get('term')));

        $search = self::__prepareSearchQuery($query, $limit);

        $item_score = [];

        if (!empty($search) && is_array($search) && 0 < sizeof($search)) {

            foreach ($search as $row) {

                $result = Product::find()
                    ->select('id')
                    ->where('name like "%' . $row . '%" and status = "1"')
                    ->limit($limit)
                    ->all();

                if ($result) {
                    foreach ($result as $r) {
                        if (!empty($item_score[$r['id']])) {
                            $item_score[$r['id']]++;
                        } else {
                            $item_score[$r['id']] = 1;
                        }
                    }
                }
            }
        }

        arsort($item_score);

        $queryFindedIds = array_keys($item_score);

        $items = self::getItemsByIds($queryFindedIds, $limit, $auto);

        $result_count_items = sizeof($items);

        if ($auto) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $items;
        } else {

            $itemsFound = '';

            foreach ($items as $item) {

                if (!empty($item->categoryLink->category_id)) {

                    $category = Category::find()->where(['id' => $item->categoryLink->category_id])->one();

                    $cardUrl = Url::to(['product/index', 'category' => $category->slug, 'slug' => $item->slug]);

                } else {
                    $cardUrl = Url::to(['product/index', 'slug' => $item->slug]);
                }

                $itemsFound .= $this->renderPartial('/product/card/large' , ['product' => $item, 'url' => $cardUrl, 'category' => $category], true, false);
            }

            return $this->render('index', [
                'query' => $query,
                'itemsFound' => $itemsFound,
                'result_limit' => $limit,
                'result_count_items' => $result_count_items
            ]);
        }

    }


    //данные найденных товаров
    public function getItemsByIds($ids, $limit = 999, $prepareAuto = false)
    {
        $result = array();

        if (!empty($ids)) {

            $ids_str = implode (',', $ids);

            $order_by_expression = new \yii\db\Expression("FIELD ( `id`, " . $ids_str . " )");

            $items = Product::find()
                ->where(['id' => $ids])
                ->orderBy([$order_by_expression])
                ->limit($limit)
                ->all();

            if ($prepareAuto) {
                $result = self::__prepareAuto($items);
            } else {
                $result = $items;
            }

        }

        return $result;
    }

    private function __prepareAuto($data)
    {
        $resArr = array();

        foreach ($data as $item) {

            if (!empty($item->name)) {

                //готовим картинку

                $mainImage = $item->getMainImage();

                if (!empty($mainImage)) {
                    $img_str = Html::img($mainImage, ['height' => 50, 'max-width' => 50, 'alt' => '']);
                } else {
                    $img_str = '<div style="width: 50px; height: 50px; display: inline-block;"></div>';
                }

                if (!empty($item->categoryLink->category_id)) {

                    $category = Category::find()->where(['id' => $item->categoryLink->category_id])->one();

                    $item_card_url = Url::to(['product/index', 'category' => $category->slug, 'slug' => $item->slug]);

                } else {
                    $item_card_url = Url::to(['product/index', 'slug' => $item->slug]);
                }

                $marker_stock = '';

                switch ($item->status) {
                    case 1:
                        $marker_stock = "<span style='color: darkgreen; font-size: 16px; font-weight: bold' title='В наличии'>";
                        $marker_stock .= "&bull;";
                        $marker_stock .= "</span>";
                        break;
                    case 0:
                        $marker_stock = "<span style='color: lightgreen; font-size: 14px; font-weight: bold' title='Нет в наличии'>";
                        $marker_stock .= "&bull;";
                        $marker_stock .= "</span>";
                        break;

                }

                $marker_stock .= "&nbsp;";

                $resArr[] = [
                    'label' => $item->name,
                    'value' => $item->name,
                    'id' => $item->id,
                    'url' => $item_card_url,
                    'img' => $img_str,
                    'price' => $item->getPrice(),
                    'marker_stock' => $marker_stock,
                ];

            }
        }

        return $resArr;

    }


    private function __prepareSearchQuery($search)
    {
        $replace_rules = array(',', '"', '\\', '/', ')', '(', "'", ';' , '*');
        $search_parts = explode(' ', str_replace($replace_rules, ' ', $search));
        $name_parts = [];
        foreach ($search_parts as $key => $part) {
            $part = trim($part);
            if (empty($part) || 2 > strlen($part)) {
                unset($search_parts[$key]);
            } else {
                $name_parts[$key] = mb_strtolower(trim($search_parts[$key]), 'UTF-8');
            }
        }
        return $name_parts;
    }


}