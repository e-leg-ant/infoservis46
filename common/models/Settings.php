<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $group
 * @property string $key
 * @property string $value
 */

class Settings extends ActiveRecord
{

    public static function tableName()
    {
        return 'settings';
    }

    public static final function get($key)
    {
        $result = Yii::$app->cache->get('setting__key-' . $key);

        if (!$result) {
            $result = self::find()->where(['key' => $key])->one();
        }

        Yii::$app->cache->set('setting__key-' . $key, $result, 10);

        return (isset($result->value)) ? $result->value : false;
    }

    public static final function getGroup($group)
    {
        return self::find()->where(['group' => $group])->all();
    }


}
