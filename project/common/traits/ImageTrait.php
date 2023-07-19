<?php

namespace common\traits;

use Yii;

trait ImageTrait
{
    /**
     * @var \common\services\ImageService|null
     */
    private $imageService;

    public function init()
    {
        parent::init();

        $this->imageService = Yii::$app->imageService;
    }

    /**
     * @param $sourceAttr
     * @param null $width
     * @param null $height
     * @param int $quality
     * @return string
     */
    public function getThumbnail($sourceAttr, $width = null, $height = null, $quality = 90)
    {
        return $this->imageService->getThumbnail($this->{$sourceAttr}, $width, $height, $quality);
    }
}
