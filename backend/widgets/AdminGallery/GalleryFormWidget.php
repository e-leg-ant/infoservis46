<?php

namespace backend\widgets\AdminGallery;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use backend\widgets\AdminGallery\GalleryFormWidgetAsset;
use Yii;

/**
 * Form gallery widget. New gallery state will be submitted when submiting form.
 */
class GalleryFormWidget extends Widget
{
    
    /**
     * Model associated with the form. Can be either ActiveRecord model 
     * or separate form model with GalleryFormBehaviour attached.
     * @var yii\base\Model
     */
    public $formModel;
    
    /**
     * Active Record model with attached GalleryBehaviour. 
     * If it is the same model as formModel, leave this field as null
     * @var yii\db\ActiveRecord
     */
    public $mainModel = null;
    
    /**
     * Image item width
     * @var integer
     */
    public $imageWidth = false;
    
    /**
     * Image item height
     * @var integer
     */
    public $imageHeight = 200;
    
    /**
     * The maximum number of images uploaded at one form submit. 
     * 0 means unlimited.
     * @var integer
     */
    public $maxFilesUploaded = 0;
    
    /**
     * The maximum number of model images that can be riched.
     * 0 means unlimited.
     * @var integer
     */
    public $maxFilesTotal = 0;
    
    /**
     * Whether render new images input
     * @var boolean
     */
    public $renderInput = true;
    
    /**
     * Custom messages
     * @var string[]
     */
    public $messages = [];
    
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->i18n->translations['model-gallery'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@backend/widgets/AdminGallery/messages',
            'fileMap' => [
                'model-gallery' => 'default.php',
            ],
        ];
        
        $defaultMessages = [
            'maxFilesUploadedError' => Yii::t('model-gallery', 'Error! Maximum number of files should not exceed {number}', ['number' => $this->maxFilesUploaded]),
            'maxFilesTotalError' => Yii::t('model-gallery', 'Error! Maximum total number of images should not exceed {number}', ['number' => $this->maxFilesTotal]),
            'buttonLabelLoad' => Yii::t('model-gallery', 'Load'),
            'buttonLabelClear' => Yii::t('model-gallery', 'Clear'),
            'deleteCheckboxLabel' => Yii::t('model-gallery', 'Delete'),
        ];
        $this->messages = ArrayHelper::merge($defaultMessages, $this->messages);
    }
    
    /**
     * @inheritDoc
     */
    public function run()
    {
        if ($this->mainModel === null) {
            $this->mainModel = $this->formModel;
        }
        GalleryFormWidgetAsset::register($this->view);
        $wCss = $this->imageWidth ? "width: {$this->imageWidth}px;" : '';
        $hCss = $this->imageHeight ? "height: {$this->imageHeight}px;" : '';
        $this->view->registerCss(
            ".form-gallery-list li { $wCss $hCss }"
        );
        
        // sortable items
        $items = [];
        foreach ($this->mainModel->galleryImages as $image) { 
            $items[] = [
                'content' => $this->render('_formWidgetItem', [
                    'image' => $image,
                ]),
                'options' =>  [
                    'data-image-id' => $image->id,
                    'class' => 'ui-state-default ui-sortable-handle form-gallery-item',
                ]
            ];
        }
        
        return $this->render('formWidget', [
            'items' => $items,
        ]);
    }
}