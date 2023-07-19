<?php

namespace frontend\widgets;

use frontend\widgets\assets\FileUploadWidgetAsset;
use yii\base\Widget;
use yii\helpers\Url;

class FileUploadWidget extends Widget {

    /**
     * @var string
     */
    public $fileInputSelector = '.js-upload input';

    /**
     * @var string
     */
    public $inputSelector;

    /**
     * @var string
     */
    public $previewSelector;

    /**
     * @var string
     */
    public $loadingSelector;

    public function run()
    {
        parent::run();

        $this->view->registerJsVar('__file_upload_widget_data', [
            'uploadUrl' => Url::to(['upload/index']),
            'fileInputSelector' => $this->fileInputSelector,
            'inputSelector' => $this->inputSelector,
            'previewSelector' => $this->previewSelector,
            'loadingSelector' => $this->loadingSelector,
        ]);

        FileUploadWidgetAsset::register($this->view);
    }

}