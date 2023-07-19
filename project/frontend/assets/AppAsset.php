<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\widgets\PjaxAsset;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/sources';

    public $css = [
        'app/css/extra.sass',
    ];

    public $js = [
        //'app/js/extra.js',
    ];

    public $depends = [
        SourceAsset::class,
        PjaxAsset::class
    ];
}
