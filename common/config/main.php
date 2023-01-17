<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=infoservis46',
            'username' => 'infoservis46',
            'password' => '1nf0s4rv1sh',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'yii\symfonymailer\Mailer',
                'scheme' => 'smtp',
                'host' => 'smtp.yandex.ru',
                'username' => 'lexpro@yandex.ru',
                'password' => 'eqhsonvdqtrdhtce',
                'port' => '465',
                'encryption' => 'ssl',
                //'dsn' => 'native://default',
            ],
        ],
    ],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ]
];
