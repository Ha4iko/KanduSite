<?php

namespace frontend\controllers\tournament;

use frontend\models\Tournament;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Prize controller
 */
class PrizeController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/prize');

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
        $model = $this->findModelById($id);

        $post = Yii::$app->request->post();
        unset($post['TournamentPrize']['%i%']);
        unset($post['TournamentPrize']['%j%']);

        if ($model->load($post) && $model->save()) {
            return $this->redirect(['tournament/prizes', 'slug' => $model->slug]);
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
    protected function findModelById($id)
    {
        $model = Tournament::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tournament not found');
    }

}
