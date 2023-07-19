<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\widgets\PjaxAsset;

/**
 * Main frontend application asset bundle.
 */
class DatepickerWithClearAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/datepicker-with-clear.js',
    ];

    public $depends = [
        SourceAsset::class,
    ];
}
