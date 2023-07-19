<?php

namespace common\services;

use Yii;
use Imagine\Image\ImageInterface;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class ImageService
 * @package common\services
 */
class ImageService
{
    private $cachePath = '/cache/thumbs';

    private $noImageFile = '/images/no-image.png';

    private $webPath;

    public function __construct($config = [])
    {
        $this->webPath = $config['webPath'] ?? Yii::getAlias('@frontend/web');
    }

    public function getThumbnail($source, $width = null, $height = null, $quality = 90)
    {
        if (!$source) {
            return $this->noImageFile;
        }
        $source = '/' . $source;

        if (!$width && !$height) {
            return $source;
        }

        $dir = $this->webPath;

        if (Yii::$app->params['uploads_prefix']) {
            $source = Yii::$app->params['uploads_prefix'] . $source;
            $source = str_replace('//', '/', $source);
            $source = str_replace(':/', '://', $source);
            $source = str_replace(' ', '%20', $source);
        }

        if (substr($source, 0, 4) === 'http') {
            return $source;
        } else {
            $source = $dir . $source;
            $source = str_replace('//', '/', $source);
            $source = urldecode($source);
        }

        $info = pathinfo($source);
        $basePath = $this->cachePath;
        if (!is_dir($dir . $basePath)) {
            mkdir($dir . $basePath, 0777, true);
        }
        $filename = 'thumb_' . $width . '_' . $height . '_' . crc32($source) . '.' . strtolower($info['extension']);
        $path = $dir . $basePath . '/' . $filename;
        if (file_exists($path)) {
            return $basePath . '/' . $filename;
        }
        try {
            Image::thumbnail($source, $width, $height)->save($path, ['quality' => $quality]);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            Yii::error($e);
            Image::thumbnail($dir . $this->noImageFile, $width, $height)->save($path, ['quality' => $quality]);
        }
        return $basePath . '/' . $filename;
    }

    /**
     * @param Model $modelFrom
     * @param ActiveRecord $modelTo
     * @param string $attribute
     * @param string $entity
     * @return bool
     */
    private function uploadForm($modelFrom, $modelTo, $attribute, $entity) {
        $image = UploadedFile::getInstance($modelFrom, $attribute);
        if ($image instanceof UploadedFile) {
            $filename = uniqid('img_') . '.' . $image->extension;
            $path = '/storage/images/' . $entity . '/';
            $dir = Yii::getAlias('@frontend/web') . $path;
            !file_exists($dir) && mkdir($dir, 0777, true);
            $image && $image->saveAs($dir . $filename) && $modelTo->$attribute = $path . $filename;
        }

        return true;
    }

    /**
     * @param Model $modelFrom
     * @param ActiveRecord $modelTo
     * @param string $attribute
     * @param string $entity
     * @return bool
     */
    public function upload($modelFrom, $modelTo, $attribute, $entity) {
        $image = UploadedFile::getInstance($modelFrom, $attribute);
        if ($image instanceof UploadedFile) {
            $filename = uniqid('img_') . '.' . $image->extension;
            $path = '/storage/images/' . $entity . '/';
            $dir = Yii::getAlias('@frontend/web') . $path;
            !file_exists($dir) && mkdir($dir, 0777, true);
            $image && $image->saveAs($dir . $filename) && $modelTo->$attribute = $path . $filename;
        }

        return true;
    }
}