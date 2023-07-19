<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['bootstrapCommon'],
    'components' => [
        'bootstrapCommon' => [
            'class' => 'common\components\Bootstrap',
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'imageService' => [
            'class' => \common\services\ImageService::class,
        ],
    ],

];
