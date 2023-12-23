<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'animal_shelter',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Veronika',
            'parsers' => ['application/json' => 'yii\web\JsonParser']
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            //'enableAutoLogin' => true,
            'enableSession' => false, //
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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

        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) 
                {
                    $response = $event->sender;
                    if ($response->data !== null && $response->statusCode==401) 
                        {
                            $response->data = ['error'=>['code'=>401, 'message'=>'Unauthorized',
                            'errors'=>['phone'=>'login or password incorrect']]];
                            header('Access-Control-Allow-Origin: *');
                            header('Content-Type: application/json');
                        }
                },
            ],

        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
            /*['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
            ['class' => 'yii\rest\UrlRule', 'controller' => 'tariff'],
            ['class' => 'yii\rest\UrlRule', 'controller' => 'order'],
            ['class' => 'yii\rest\UrlRule', 'controller' => 'category'],*/
            
            //пользователи
            
            'POST register' => 'user/create',
            'POST login' => 'user/login',

            'GET animals' => 'animal/view',

            'POST record_create' => 'record/create',
            'DELETE record_delete/<id>' => 'record/delete',

            //админ

            'PATCH status_change/<id>' => 'record/change',

            ],
        ],
    
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
