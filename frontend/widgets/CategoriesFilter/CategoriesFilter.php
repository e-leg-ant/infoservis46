<?php

namespace frontend\widgets\CategoriesFilter;

use common\models\ProductsCategories;
use Yii;
use common\models\Product;
use common\models\Brand;
use common\models\ProductDiscount;

class CategoriesFilter extends \yii\bootstrap\Widget
{
    public $category;
    public $discountsWhere;
    public $brandsWhere;

    public function run()
    {

        // Получение типов скидок товаров
        $discounts = ProductDiscount::find()->all();

        // Получение брендов товаров
        $products = ProductsCategories::find()->select('distinct `product_id` as product_id')->where(['category_id' => $this->category])->all();

        $products_id = [];

        if (!empty($products) && is_array($products)) {
            foreach ($products as $p) {
                $products_id[] = $p['product_id'];
            }
        }

        $brands_available = Product::find()->select('distinct `brand_id` as brand_id')->where(['in','id', $products_id])->asArray()->all();

        $brands_available_id = [];

        if (!empty($brands_available) && is_array($brands_available)) {
            foreach ($brands_available as $ba) {
                $brands_available_id[] = $ba['brand_id'];
            }
        }

        $brands = Brand::find()->where(['in', 'id', $brands_available_id])->all();

        return $this->render('filter', [
            'discounts' => $discounts,
            'brands' => $brands,
            'discountsWhere' => $this->discountsWhere,
            'brandsWhere' => $this->brandsWhere,            
            'category' => $this->category
        ]);

    }
}