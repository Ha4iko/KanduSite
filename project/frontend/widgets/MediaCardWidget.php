<?php

namespace frontend\widgets;

use frontend\models\Media;
use common\widgets\BaseWidget;
use yii\web\HttpException;

class MediaCardWidget extends BaseWidget
{
    /**
     * @var Media
     */
    public $model;

    /**
     * @var bool
     */
    public $adminMode = false;

    /**
     * Init widget
     * @throws HttpException
     */
    public function init()
    {
       parent::init();

       if (!is_object($this->model)) {
           throw new HttpException(500);
       }
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('card', [
            'model' => $this->model,
            'adminMode' => $this->adminMode,
        ]);
    }
}

