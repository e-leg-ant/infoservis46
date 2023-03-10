<?php
namespace backend\widgets\AdminGallery;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\validators\Validator;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use Yii;

class GalleryFormBehavior extends Behavior
{

    /**
     * The maximum number of images uploaded at one form submit. 
     * 0 means unlimited.
     * It will be thrown Exception when exceeded.
     * @var integer
     */
    public $maxFilesUploaded = 0;
    
    /**
     * The maximum number of model images that can be riched using this behavior.
     * 0 means unlimited.
     * It will be thrown Exception when exceeded.
     * @var integer
     */
    public $maxFilesTotal = 0;
    
    /**
     * Whether perform automatic calling of saveGallery() method after saving 
     * Active Record model.
     * @var boolean
     */
    public $autosave = true;
    
    /**
     * Attribute for uploaded image files
     * @var string
     */
    public $galleryImageFiles;

    /**
     * Attribute for deleting images list
     * @var string
     */
    public $galleryImagesDelete;

    /**
     * Attribute for images order
     * @var string
     */
    public $galleryImagesOrder;

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [];
        if ($this->autosave && $this->owner instanceof ActiveRecord) {
            $events[ActiveRecord::EVENT_AFTER_INSERT] = 'afterSave';
            $events[ActiveRecord::EVENT_AFTER_UPDATE] = 'afterSave';
        }
        return $events;
    }

    /**
     * Saving images after saving main model 
     * (in case when main model is an instance of ActiveRecord)
     * @param  $event
     */
    public function afterSave($event)
    {
        if ($this->autosave && $this->owner instanceof ActiveRecord && !$this->owner->hasErrors()) {
            $this->saveGallery($this->owner);
        }
    }

    /**
     * Saves images order, new images and deletes images for $mainModel. 
     * @param ActiveRecord  $mainModel
     * @param boolean       $validateAttributes
     */
    public function saveGallery(ActiveRecord $mainModel, $validateAttributes = true)
    {
        $request = Yii::$app->request;
        // loading params manually
        $this->owner->galleryImagesDelete = ArrayHelper::getValue($request->post($this->owner->formName()), 'galleryImagesDelete');
        $this->owner->galleryImagesOrder = ArrayHelper::getValue($request->post($this->owner->formName()), 'galleryImagesOrder');
        $this->owner->galleryImageFiles = \yii\web\UploadedFile::getInstances($this->owner, 'galleryImageFiles');
        
        $currentCount = $mainModel->getGalleryImages()->count();
        $filesCount = count($this->owner->galleryImageFiles);
        if ($filesCount > 0) {
            if (((int)$this->maxFilesUploaded > 0 && $filesCount > (int)$this->maxFilesUploaded) || ((int)$this->maxFilesTotal > 0 && ($currentCount + $filesCount) > (int)$this->maxFilesTotal)) {
                throw new \Exception('Max images count error');
            } 
        }
        
        if ($validateAttributes) {
            $validators = $this->owner->getValidators();
            $validators->append(Validator::createValidator('backend\widgets\AdminGallery\validators\GalleryAdditionalDataValidator', $this->owner, ['galleryImagesDelete', 'galleryImagesOrder']));
            $validators->append(Validator::createValidator('image', $this->owner, ['galleryImageFiles'], ['maxFiles' => 0]));
            if (!$this->owner->validate(['galleryImageFiles', 'galleryImagesDelete', 'galleryImagesOrder'], false)) {
                return;
            }
        }
        
        $deletingImages = Json::decode($this->owner->galleryImagesDelete);
        $order = Json::decode($this->owner->galleryImagesOrder);
        
        if (is_array($order) && count($order) > 0) {
            $mainModel->saveOrder($order);
        }
        if (is_array($deletingImages) && count($deletingImages) > 0) {
            $mainModel->deleteImages($deletingImages);
        }
        if (is_array($this->owner->galleryImageFiles) && count($this->owner->galleryImageFiles) > 0) {
            $mainModel->saveImages($this->owner->galleryImageFiles);
        }

        // clear attribute
        $this->owner->galleryImageFiles = null;
        // unset main model gallery images relation for showing updated images in this request
        unset($mainModel->galleryImages);
    }
}
