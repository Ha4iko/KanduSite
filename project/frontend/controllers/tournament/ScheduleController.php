<?php

namespace frontend\controllers\tournament;

use frontend\models\ScheduleForm;
use frontend\models\Tournament;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Schedule controller
 */
class ScheduleController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/schedule');

        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateTournament'],
                        'roleParams' => function ($rule) {
                            $tournamentId = (integer) Yii::$app->request->get('id');
                            if (!$tournamentId) {
                                $tournamentId = (integer) ArrayHelper::getValue(
                                    Yii::$app->request->post(), 'Tournament.id', 0
                                );
                            }
                            return [
                                'tournamentId' => $tournamentId,
                            ];
                        }
                    ],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => ['update']
            ],
        ];
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findFormById($id);

        $post = Yii::$app->request->post();
        unset($post['TournamentSchedule']['%i%']);

        if ($model->load($post) && $model->validate() && $model->loadData()) {
            //var_dump($model->tournamentSchedules);
            try {
                if ($model->save()) {
                    return $this->redirect(['tournament/schedule', 'slug' => $model->slug]);
                }
            } catch (\Exception $e) {

            }
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return Tournament
     * @throws NotFoundHttpException
     */
    protected function findFormById($id)
    {
        $model = ScheduleForm::findOne($id)->initData();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tournament not found');
    }

    // /**
    //  * @param $id
    //  * @return Tournament
    //  * @throws NotFoundHttpException
    //  */
    // protected function findModelById($id)
    // {
    //     $model = Tournament::findOne($id);
    //
    //     if ($model !== null) {
    //         return $model;
    //     }
    //
    //     throw new NotFoundHttpException('Tournament not found');
    // }

}
