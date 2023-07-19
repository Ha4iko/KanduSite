<?php

namespace frontend\controllers\tournament;

use frontend\models\Tournament;
use frontend\models\WinnersForm;
use frontend\models\WinnersTeamForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;

/**
 * Winner controller
 */
class WinnerController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/winner');

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
                        'actions' => ['update', 'update-team'],
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
                'only' => ['update', 'update-team']
            ],
        ];
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionUpdate($id)
    {
        $model = $this->findFormById($id);
        $tournament = $model->tournament;
        $nicks = $model->getTournamentParticipants();

        $prizesSpecial = $model->getPrizesSpecial();
        $prizesSecondary = $model->getPrizesSecondary();
        $prizesStandard = $tournament->getStandardPrizes();

        if (empty($prizesSpecial) && empty($prizesSecondary) && empty($prizesStandard)) {
            return $this->renderAjax('popup_empty-prizes', [
                'model' => $model
            ]);
        }

        if (empty($nicks)) {
            return $this->renderAjax('popup_empty-participants', [
                'model' => $model
            ]);
        }

        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->save()) {
            return $this->redirect(['tournament/winners', 'slug' => $tournament->slug]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'nicks' => $nicks,
        ]);
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdateTeam($id)
    {
        $model = $this->findFormTeamById($id);
        $tournament = $model->tournament;
        $teamNames = $model->getTournamentParticipants();

        $prizesSpecial = $model->getPrizesSpecial();
        $prizesSecondary = $model->getPrizesSecondary();
        $prizesStandard = $tournament->getStandardPrizes();

        if (empty($prizesSpecial) && empty($prizesSecondary) && empty($prizesStandard)) {
            return $this->renderAjax('popup_empty-prizes', [
                'model' => $model
            ]);
        }

        if (empty($teamNames)) {
            return $this->renderAjax('popup_empty-participants', [
                'model' => $model
            ]);
        }

        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->save()) {
            return $this->redirect(['tournament/winners', 'slug' => $tournament->slug]);
        }

        return $this->renderAjax('update-team', [
            'model' => $model,
            'teamNames' => $teamNames,
        ]);
    }

    /**
     * @param $id
     * @return WinnersForm
     * @throws HttpException
     */
    protected function findFormById($id)
    {
        return (new WinnersForm(['tournament_id' => $id]))->loadFromTournamentModel();
    }

    /**
     * @param $id
     * @return WinnersTeamForm
     * @throws HttpException
     */
    protected function findFormTeamById($id)
    {
        return (new WinnersTeamForm(['tournament_id' => $id]))->loadFromTournamentModel();
    }

}
