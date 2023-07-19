<?php

namespace console\controllers;

use common\services\Bracket\RelegationGeneratorService;
use Yii;
use yii\console\Controller;

class BracketController extends Controller
{

    public function actionGen() {
         $relegationService = Yii::$container->get(RelegationGeneratorService::class);
         $relegationService->debug = true;
         $result = $relegationService->generate(16, false, false);
         print_r($result);
    }

}