<?php

namespace frontend\widgets\Gallery;

use backend\widgets\AdminGallery\Image;
use Yii;

class Gallery extends \yii\bootstrap\Widget
{
    public $id;
    public $view;

    public function run()
    {

        $images = Image::findAll(['item_id' => $this->id]);

        $data = [];

        foreach ($images as $image) {

            $image->extension = 'jpg';

            $data['images'][] = [
                'src' => Yii::getAlias('@web/storage/gallery') . $image->getUrl('original'),
                'preview_src' => Yii::getAlias('@web/storage/gallery') . $image->getUrl('preview'),
                'title' => $image->alt,
                'alt' => $image->alt,
            ];
        }

        $view = (!empty($this->view)) ? $this->view : 'view';

        return $this->render($view, $data);

    }
}