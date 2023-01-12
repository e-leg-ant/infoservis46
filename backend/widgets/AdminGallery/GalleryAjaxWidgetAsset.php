<?php

namespace backend\widgets\AdminGallery;

use yii\web\AssetBundle;

class GalleryAjaxWidgetAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/AdminGallery/assets';
    public $css = [
        'css/ajax-widget.css'
    ];
    
    public $js = [
        'js/ajax-widget.js'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'backend\widgets\AdminGallery\JqueryConfirmAsset',
        'backend\widgets\AdminGallery\JqueryAjaxFileUploaderAsset',
    ];
}