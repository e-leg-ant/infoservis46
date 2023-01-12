<?php

namespace frontend\widgets\CategoriesList;

use common\models\ProductsCategories;
use Yii;
use common\models\Category;


class CategoriesList extends \yii\bootstrap\Widget
{
    public $category;

    public function run()
    {

        if (!empty($this->category)) {
            $categories = Category::find()->where(['parent_id' => $this->category])->all();
        } else {
            $categories = Category::find()->where(['parent_id' => 0])->all();
        }

        if (!empty($categories) && is_array($categories)) {

            foreach ($categories as $key => $category) {

                if (!Category::hasTreeProducts($category->id)) {
                    unset($categories[$key]);
                }
            }

        }

        return $this->render('list', [
            'categories' => $categories
        ]);

    }
}