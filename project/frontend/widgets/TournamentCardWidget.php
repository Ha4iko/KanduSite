<?php

namespace frontend\widgets;

use frontend\models\Tournament;
use common\widgets\BaseWidget;
use yii\web\HttpException;

class TournamentCardWidget extends BaseWidget
{
    /**
     * @var Tournament
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

       if (!($this->model instanceof Tournament)) {
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

