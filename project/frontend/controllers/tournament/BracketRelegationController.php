<?php

namespace frontend\controllers\tournament;

use frontend\models\BracketRelegationForm;
use frontend\models\BracketRelegationParticipantsForm;
use frontend\models\Tournament;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BracketRelegation controller
 */
class BracketRelegationController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/bracket-relegation');

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
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'update-bracket', 'update-participants'
                        ],
                        'roles' => ['updateTournament'],
                        'roleParams' => function ($rule) {
                            return [
                                'tournamentId' => Yii::$app->request->get('id'),
                            ];
                        }
                    ],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => ['update-bracket', 'update-participants']
            ],
        ];
    }

    /**
     * @param integer $id tournament id (this param name need for access rule)
     * @param integer $bracketId
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionUpdateBracket($id, $bracketId = null)
    {
        $model = $bracketId
            ? $this->findBracketRelegationFormById($bracketId)
            : new BracketRelegationForm(['tournament_id' => $id, 'editable' => 1,
                'editable_participants' => 1, 'editable_scores' => 1]);

        if ($model->load(Yii::$app->request->post()) && $model->saveForm()) {
            return $this->redirect([
                '/tournament/brackets',
                'slug' => $model->tournament->slug,
                'id' => $model->id,
            ]);
        }

        return $this->renderAjax('update-bracket', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id tournament id (this param name need for access rule)
     * @param integer $bracketId
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionUpdateParticipants($id, $bracketId)
    {
        $model = new BracketRelegationParticipantsForm([
            'bracketId' => $bracketId,
        ]);

        $tournamentParticipants = $model->tournamentParticipants;
        if (!$tournamentParticipants) {
            return $this->renderAjax('@frontend/views/_tournament/bracket/popup_empty-participants', [
                'model' => $model
            ]);
        }

        if ($model->loadForm(Yii::$app->request->post()) && $model->saveForm()) {
            return $this->redirect([
                '/tournament/brackets',
                'slug' => $model->tournament->slug,
                'id' => $model->bracketId,
            ]);
        }

        return $this->renderAjax('update-participants', [
            'model' => $model,
            'tournamentParticipants' => $tournamentParticipants,
        ]);
    }

    /**
     * @param $id
     * @return BracketRelegationForm
     * @throws NotFoundHttpException
     */
    protected function findBracketRelegationFormById($id)
    {
        $model = BracketRelegationForm::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('BracketRelegationForm not found');
    }

    /**
     * @param $id
     * @return Tournament
     * @throws NotFoundHttpException
     */
    protected function findTournamentById($id)
    {
        $model = Tournament::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tournament not found');
    }

}
