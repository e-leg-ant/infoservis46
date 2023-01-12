<?php

namespace frontend\widgets\Contacts;

use common\models\Settings;
use Yii;

class Contacts extends \yii\bootstrap\Widget
{
    public function run()
    {
        $data = [];

        $data['contact_name'] = Settings::get('config_contact_name');
        $data['address'] = Settings::get('config_address');
        $data['telephone'] = Settings::get('config_telephone');
        $data['fax'] = Settings::get('config_fax');
        $data['contact_email'] = Settings::get('config_email');

        return $this->renderHtml($this->view, $data);

    }

    protected function renderHtml($view, $data) {
        ob_start();
        include __DIR__ . '/views/' . $view . '.php';
        return ob_get_clean();
    }

}