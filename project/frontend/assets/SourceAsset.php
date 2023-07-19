<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

class SourceAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/sources/markup';

    public $js = [
        "js/libs/fitty.min.js",
        "js/libs/swiper-bundle.min.js",
        "js/libs/select2.min.js",
        "js/libs/simplebar.min.js",
        "js/libs/datepicker.min.js",
        "js/libs/datepicker.en.js",
        "js/libs/jquery.autocomplete.min.js",
        "js/libs/dragscroll.js",
        "js/libs/tooltipster.bundle.min.js",
        'js/main.js',
    ];

    public $css = [
        'sass/main.sass'
    ];

    public $depends = [
        YiiAsset::class,
    ];
}
