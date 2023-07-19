<?php

namespace frontend\controllers\tournament;

use frontend\models\Bracket;
use frontend\models\BracketTableForm;
use frontend\models\BracketTableParticipantsForm;
use frontend\models\BracketTableTeamsForm;
use frontend\models\Tournament;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BracketTable controller
 */
class BracketTableController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/bracket-table');

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
                            'update-bracket-table', 'update-bracket-table-participants',
                            'update-bracket-table-teams',
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
                'only' => ['update-bracket-table', 'update-bracket-table-participants', 'update-bracket-table-teams']
            ],
        ];
    }

    /**
     * @param integer $id tournament id (this param name need for access rule)
     * @param integer $bracketId
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdateBracketTable($id, $bracketId = null)
    {
        $tournament = $this->findTournamentById($id);

        if ($bracketId) {
            $model = $this->findBracketFormById($bracketId);
        } else {
            $model = (new BracketTableForm(['tournament_id' => $tournament->id]))->initDefaultValues();
        }

        $post = Yii::$app->request->post();

        if ($model->loadForm($post)) {
            if ($model->saveForm()) {
                return $this->redirect([
                    '/tournament/brackets',
                    'slug' => $tournament->slug,
                    'id' => $model->id,
                ]);
            }
        }

        return $this->renderAjax('update-bracket-table', [
            'model' => $model
        ]);
    }

    /**
     * @param integer $id tournament id (this param name need for access rule)
     * @param integer $bracketId
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdateBracketTableParticipants($id, $bracketId = null)
    {
        $tournament = $this->findTournamentById($id);

        if ($bracketId) {
            $model = $this->findBracketParticipantsFormById($bracketId);
        } else {
            $model = (new BracketTableParticipantsForm(['tournament_id' => $tournament->id]))->initDefaultValues();
        }

        $post = Yii::$app->request->post();

        if ($model->loadForm($post) && $model->saveForm()) {
            if ($bracketId) {
                return $this->redirect(['tournament/brackets', 'slug' => $tournament->slug, 'id' => $bracketId]);
            } else {
                return $this->redirect(['tournament/brackets', 'slug' => $tournament->slug]);
            }
        }

        return $this->renderAjax('update-bracket-table-participants', [
            'model' => $model
        ]);
    }


    /**
     * @param integer $id tournament id (this param name need for access rule)
     * @param integer $bracketId
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdateBracketTableTeams($id, $bracketId = null)
    {
        $tournament = $this->findTournamentById($id);

        if ($bracketId) {
            $model = $this->findBracketTeamsFormById($bracketId);
        } else {
            $model = (new BracketTableTeamsForm(['tournament_id' => $tournament->id]))->initDefaultValues();
        }

        $post = Yii::$app->request->post();

        if ($model->loadForm($post) && $model->saveForm()) {
            if ($bracketId) {
                return $this->redirect(['tournament/brackets', 'slug' => $tournament->slug, 'id' => $bracketId]);
            } else {
                return $this->redirect(['tournament/brackets', 'slug' => $tournament->slug]);
            }

        }

        return $this->renderAjax('update-bracket-table-teams', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return BracketTableForm
     * @throws NotFoundHttpException
     */
    protected function findBracketFormById($id)
    {
        $model = BracketTableForm::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('BracketTableForm not found');
    }

    /**
     * @param $id
     * @return BracketTableParticipantsForm
     * @throws NotFoundHttpException
     */
    protected function findBracketParticipantsFormById($id)
    {
        $model = BracketTableParticipantsForm::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('BracketTableForm not found');
    }

    /**
     * @param $id
     * @return BracketTableTeamsForm
     * @throws NotFoundHttpException
     */
    protected function findBracketTeamsFormById($id)
    {
        $model = BracketTableTeamsForm::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('BracketTableForm not found');
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
