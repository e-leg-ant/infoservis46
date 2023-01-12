<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'tablichki.net',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'ru-RU',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => 'sIf7lZilH9tvAQNopYf_YrY9XqcQK3Sh',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
            'cartId' => 'shopping_cart',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'product/<category>/<slug>' => 'product/index',
                'category/<slug>/<page:\d+>/<per-page:\d+>' => 'category/index',
                'category/<slug>' => 'category/index',
                'category' => 'category/index',
                'contacts' => 'site/contacts',
                'information/<category>/<slug>/<page:\d+>/<per-page:\d+>' => 'information/index',
                'information/<category>/<slug>' => 'information/index',
                'information/<slug>' => 'information/category',
                ['pattern' => 'sitemap', 'route' => 'seo/sitemap', 'suffix' => '.xml'],
                ['pattern' => 'price', 'route' => 'seo/yml', 'suffix' => '.xml'],
            ],
        ],
    ],
    'params' => $params,
];
