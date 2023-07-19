<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class UploadController
 * @package frontend\controllers
 */
class UploadController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'ajax' => [
                'class' => AjaxFilter::className(),
                'only' => ['upload']
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['root', 'admin', 'organizer'],
                    ]
                ],
            ],
        ];
    }

    /**
     * @return Response
     */
    public function actionIndex()
    {
        $data = UploadedFile::getInstancesByName('files');
        $files = [];

        if (empty($data)) {
            return $this->asJson($files);
        }

        // validate
        foreach ($data as $file) {
            if (
                !in_array($file->extension, ['jpg', 'jpeg', 'png']) ||
                !@is_array(getimagesize($file->tempName))
            ) {
                return $this->asJson([
                    'status' => 'fail',
                    'message' => Yii::t('global', 'Неподдерживаемый формат файла')
                ]);
            }
            if ($file->size > 8 * 1024 * 1024) {
                return $this->asJson([
                    'status' => 'fail',
                    'message' => Yii::t('global', 'Размер файла не должен превышать 8МБ')
                ]);
            }
        }

        // save
        foreach ($data as $file) {
            $filename = uniqid('img_') . '.' . $file->extension;
            $path = '/storage/images/' . date('Y/m');
            $dir = Yii::getAlias('@webroot') . $path;
            !file_exists($dir) && mkdir($dir, 0777, true);

            if ($file->saveAs($dir . DIRECTORY_SEPARATOR . $filename)) {
                $files[] = $path . DIRECTORY_SEPARATOR . $filename;
            }
        }

        return $this->asJson([
            'status' => 'ok',
            'files' => $files
        ]);
    }

}