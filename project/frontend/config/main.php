<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
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
                //'preview' => '/tournament/preview',
                'media/<slug>' => '/site-media/view',
                'media' => '/site-media/index',
                '<action:(thanks|donate|contacts|terms|privacy)>' => '/site/<action>',
                'tournaments/type/<type>' => 'tournament/index',
                'tournaments/<slug>/brackets/<id:\d+>' => '/tournament/brackets',
                'tournaments/<slug>' => '/tournament/brackets',
                'tournaments/<slug>/<action>' => '/tournament/<action>',
                'tournaments' => '/tournament/index',
                'champions' => '/tournament/champions',
                'tournament/<action>' => '/tournament/<action>',
                'cabinet/profile/<id:\d+>' => '/cabinet/profile',
                '<action:(login|logout)>' => '/cabinet/<action>',
            ],
        ],
        'assetManager' => [
            'bundles' => YII_ENV_PROD ? require __DIR__ .  '/assets-prod.php' : require __DIR__ .  '/assets.php',
            'appendTimestamp' => true,
            'forceCopy' => true,
            'linkAssets' => true,
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --no-color'],
                    'sass' => ['css', 'node-sass {from} {to}'],
                    'scss' => ['css', 'node-sass {from} {to}']
                ],
            ],
        ],
        'formatter' => [
            'nullDisplay' => '',
        ],
    ],
    'controllerMap' => [
        'rule' => 'frontend\controllers\tournament\RuleController',
        'prize' => 'frontend\controllers\tournament\PrizeController',
        'participant' => 'frontend\controllers\tournament\ParticipantController',
        'winner' => 'frontend\controllers\tournament\WinnerController',
        'tournament-media' => 'frontend\controllers\tournament\MediaController',
        'schedule' => 'frontend\controllers\tournament\ScheduleController',
        'bracket' => 'frontend\controllers\tournament\BracketController',
        'bracket-table' => 'frontend\controllers\tournament\BracketTableController',
        'bracket-relegation' => 'frontend\controllers\tournament\BracketRelegationController',
        'bracket-group' => 'frontend\controllers\tournament\BracketGroupController',
        'bracket-swiss' => 'frontend\controllers\tournament\BracketSwissController',
    ],
    'params' => $params,
];
