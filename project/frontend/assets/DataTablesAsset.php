<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class DataTablesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/datatables.min.css'
    ];

    public $js = [
        'js/datatables.min.js',
    ];

    public $depends = [
        SourceAsset::class,
    ];
}
