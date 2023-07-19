<?php

namespace frontend\controllers\tournament;

use frontend\models\Bracket;
use frontend\models\BracketCloneForm;
use frontend\models\BracketGroupForm;
use frontend\models\BracketRelegationForm;
use frontend\models\BracketSwissForm;
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
class BracketController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/bracket');

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
                            'select-type'
                        ],
                        'roles' => ['updateTournament'],
                        'roleParams' => function ($rule) {
                            return [
                                'tournamentId' => Yii::$app->request->get('id'),
                            ];
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'delete-bracket', 'clone-bracket',
                        ],
                        'roles' => ['updateTournament'],
                        'roleParams' => function ($rule) {
                            return [
                                'tournamentId' => Bracket::findOne(Yii::$app->request->get('id'))->tournament_id,
                            ];
                        }
                    ],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => ['select-type', 'delete-bracket', 'clone-bracket']
            ],
        ];
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionSelectType($id)
    {
        $model = $this->findTournamentById($id);

        return $this->renderAjax('select-type', [
            'model' => $model
        ]);
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionDeleteBracket($id)
    {
        if (null === $model = Bracket::findOne($id)) {
            throw new NotFoundHttpException('Bracket not found');
        }

        if (Yii::$app->request->isPost) {
            $tournament = $model->tournament;
            $model->delete();
            return $this->redirect(['tournament/brackets', 'slug' => $tournament->slug]);
        }

        return $this->renderAjax('delete-bracket', [
            'model' => $model
        ]);
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionCloneBracket($id)
    {
        if (null === $bracket = Bracket::findOne($id)) {
            throw new NotFoundHttpException('Bracket not found');
        }

        $cloneForm = new BracketCloneForm(['id' => $bracket->id, 'old_title' => $bracket->title]);

        if ($cloneForm->load(Yii::$app->request->post()) &&
            ($newId = $cloneForm->clone($bracket->id, $bracket->bracket_type))
        ) {
            return $this->redirect(['tournament/brackets', 'slug' => $bracket->tournament->slug, 'id' => $newId]);
        }

        return $this->renderAjax('clone-bracket', [
            'model' => $cloneForm
        ]);
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
