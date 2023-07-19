<?php

namespace frontend\controllers\tournament;

use common\models\Bracket\Swiss;
use common\models\Bracket\Swiss\Duel;
use common\models\Bracket\Swiss\PlayerDuel;
use common\models\Bracket\Swiss\TeamDuel;
use common\models\BracketGroupPlayerDuel;
use common\models\BracketGroupTeamDuel;
use common\services\Bracket\SwissService;
use frontend\models\BracketGroupForm;
use frontend\models\BracketGroupParticipantsForm;
use frontend\models\BracketSwissForm;
use frontend\models\BracketSwissParticipantsForm;
use frontend\models\Tournament;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BracketSwiss controller
 */
class BracketSwissController extends Controller
{

    /**
     * @var SwissService
     */
    private $bracketSwissService;

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/bracket-swiss');

        return parent::beforeAction($action);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        $this->bracketSwissService = Yii::$container->get(SwissService::class);
        parent::init();
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
                            'update-bracket', 'update-participants', 'generate-round'
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
            ? $this->findBracketSwissFormById($bracketId)
            : new BracketSwissForm(['tournament_id' => $id, 'editable' => 1,
                'editable_participants' => 1, 'editable_scores' => 0]);

        if ($model->loadData(Yii::$app->request->post(), $id) && $model->saveForm()) {
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
        $model = new BracketSwissParticipantsForm([
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
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionGenerateRound() {
        $data = Yii::$app->request->post();
        $roundId = intval($data['roundId']) ?? null;
        $bracketId = intval($data['bracketId']) ?? null;
        $bracket = Swiss::findOne($bracketId);
        $round = Swiss\Round::findOne($roundId);

        if (!$bracket || !$round) {
            throw new BadRequestHttpException();
        }

        if (!$round->prevRound->completed || $round->filled) {
            throw new BadRequestHttpException();
        }

        $this->bracketSwissService->generatePairs($roundId);
        return $this->redirect(Url::to(['tournament/brackets', 'slug' => $bracket->tournament->slug, 'id' => $bracket->id]) . '#round' . $round->order);
    }

    /**
     * @param $id
     * @return BracketSwissForm
     * @throws NotFoundHttpException
     */
    protected function findBracketSwissFormById($id)
    {
        $model = BracketSwissForm::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('BracketSwissForm not found');
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
