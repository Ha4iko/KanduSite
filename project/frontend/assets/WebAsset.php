<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\widgets\PjaxAsset;

/**
 * Main frontend application asset bundle.
 */
class WebAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/extra.js',
        'js/ui.js',
    ];

    public $depends = [
        SourceAsset::class,
        PjaxAsset::class
    ];
}
