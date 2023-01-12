<?php

namespace frontend\widgets\InformationList;

use Yii;
use common\models\Information;
use common\models\InformationCategory;


class InformationList extends \yii\bootstrap\Widget
{
    public $category;
    public $h1;
    public $view;

    public function run()
    {

        $information = Information::find()->where(['category_id' => $this->category ])->orderBy(['order' => SORT_ASC])->all();

        $category = InformationCategory::find()->where(['id' => $this->category ])->one();

        $view = (!empty($this->view)) ? $this->view : 'articles';

        return $this->render($view, [
            'information' => $information,
            'category' => $category,
            'h1' => (!empty($this->h1) ? $this->h1 : 'h1'),
        ]);

    }
}