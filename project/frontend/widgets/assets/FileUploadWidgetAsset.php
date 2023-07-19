<?php

namespace frontend\widgets\assets;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;

class FileUploadWidgetAsset extends AssetBundle {

    public $sourcePath = __DIR__ . '/sources';

    public $js = [
        'js/file-upload-widget.js'
    ];

    public $depends = [
        AppAsset::class
    ];
}