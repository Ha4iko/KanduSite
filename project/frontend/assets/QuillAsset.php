<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class QuillAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
//        'css/quill.core.css',
        'css/quill.snow-idl.css'
    ];

    public $js = [
        'js/quill.min.js',
    ];

    public $depends = [
        SourceAsset::class,
    ];
}
