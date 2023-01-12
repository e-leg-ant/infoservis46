<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * CallBackForm is the model behind the contact form.
 */
class CallBackForm extends Model
{
    public $name;
    public $phone;
    public $text;
    public $file;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'phone'], 'required'],
            [['text'], 'string'],
            [['file'], 'file', 'skipOnEmpty' => true],
            ['verifyCode', 'captcha'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон',
            'text' => 'Ваш вопрос',
            'file' => 'Файл',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        $subject = 'Обратный звонок';

        $body = 'Имя: ' . $this->name . PHP_EOL;
        $body .= 'Телефон: ' . $this->phone . PHP_EOL;
        $body .= 'Вопрос: ' . $this->text . PHP_EOL;

        $message = Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom($email)
            ->setSubject($subject)
            ->setTextBody($body);

        $attachment = UploadedFile::getInstances($this, 'file');

        if (!empty($attachment)) {

            foreach ($attachment as $file) {
                $filename = Yii::getAlias('@webroot') . '/storage/attached/' . $file->baseName. '.' . $file->extension;
                $file->saveAs($filename);
                $message->attach($filename);
            }

        }

        return $message->send();
    }
}
