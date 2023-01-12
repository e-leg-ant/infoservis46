<?php

namespace backend\widgets\AdminGallery;

use yii\web\AssetBundle;

class GalleryFormWidgetAsset extends AssetBundle
{
    public $sourcePath = '@matvik/modelGallery/assets';
    public $css = [
        'css/form-widget.css'
    ];
    
    public $js = [
        'js/form-widget.js'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'backend\widgets\AdminGallery\JqueryConfirmAsset',
    ];
}