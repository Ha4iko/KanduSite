<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
\Yii::setAlias('@webroot', __DIR__ . '/../web');
\Yii::setAlias('@web', '/');

return [
    'jsCompressor' => 'java -jar includes/closure-compiler-v20190325.jar --js {from} --js_output_file {to}',
    'cssCompressor' => 'node-sass --output-style compressed {from} {to}',
    'deleteSource' => false,
    'bundles' => [
        'frontend\assets\AppAsset',
        'frontend\assets\SourceAsset',
    ],
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/dist',
            'baseUrl' => '@web/dist',
            'js' => 'all-{hash}.js',
            'css' => 'all-{hash}.css',
        ]
    ],
    'assetManager' => [
        'basePath' => '@webroot/dist',
        'baseUrl' => '@web/dist',
        'converter' => [
            'class' => 'yii\web\AssetConverter',
            'commands' => [
                'less' => ['css', 'lessc {from} {to} --no-color'],
                'sass' => ['css', 'node-sass --output-style compressed {from} {to}'],
                'scss' => ['css', 'node-sass --output-style compressed {from} {to}']
            ],
        ],
        'bundles' => [
        ]
    ],
];