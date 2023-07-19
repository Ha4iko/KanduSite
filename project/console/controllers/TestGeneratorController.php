<?php

namespace console\controllers;

use common\services\Bracket\RelegationGeneratorService;
use Yii;
use yii\console\Controller;

/**
 * use:
 */
class TestGeneratorController extends Controller
{
    public function actionIndex() {
        for ($i = 1; $i < 21; $i++) {
            Yii::$app->db->createCommand()->insert('player', [
                'nick' => 'Player_' . $i,
            ])->execute();
        }

        for ($i = 1; $i < 21; $i++) {
            Yii::$app->db->createCommand()->insert('team', [
                'name' => 'Team_' . $i,
            ])->execute();
        }
    }
}