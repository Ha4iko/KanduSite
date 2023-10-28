<?php

namespace frontend\controllers;

use common\models\Bracket\BracketFacade;
use common\models\Bracket\Group;
use common\models\Bracket\Swiss;
use common\services\Bracket\RelegationService;
use common\services\CloneService;
use frontend\models\Bracket;
use frontend\models\BracketGroupDuelsForm;
use frontend\models\BracketRelegationDuelsForm;
use frontend\models\BracketSwissDuelsForm;
use frontend\models\BracketTableRowForm;
use frontend\models\BracketTableRowTeamForm;
use frontend\models\search\BracketTableRowSearch;
use frontend\models\search\BracketTableRowTeamSearch;
use frontend\models\search\ChampionSearch;
use frontend\models\search\TournamentSearch;
use frontend\models\Tournament;
use common\services\TournamentService;
use frontend\models\TournamentForm;
use Yii;
use yii\db\Transaction;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\services\ScheduleService;

/**
 * Tournament controller
 */
class TournamentController extends Controller
{
    /** @var Tournament */
    private $tournament;

    /** @var Bracket */
    private $bracket;

    /** @var boolean */
    private $preview;

    /** @var ScheduleService */
    private $scheduleService;

    /**
     * @var TournamentService
     */
    private $tournamentService;

    /**
     * @var RelegationService
     */
    private $bracketRelegationService;

    /**
     * @var CloneService
     */
    private $cloneService;

    /**
     * @var Transaction
     */
    private $previewTransaction;

    /**
     * TournamentController constructor.
     * @param $id
     * @param $module
     * @param ScheduleService $scheduleService
     * @param TournamentService $tournamentService
     * @param RelegationService $bracketRelegationService
     * @param CloneService $cloneService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        ScheduleService $scheduleService,
        TournamentService $tournamentService,
        RelegationService $bracketRelegationService,
        CloneService $cloneService,
        $config = [])
    {
        $this->tournamentService = $tournamentService;
        $this->scheduleService = $scheduleService;
        $this->bracketRelegationService = $bracketRelegationService;
        $this->cloneService = $cloneService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \Throwable
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->get('preview', false)) {
            Yii::$app->params['preview'] = true;
            $this->preview = true;
            $this->previewTransaction = Yii::$app->db->beginTransaction();
        }

        if (in_array($action->id, [
            'rules', 'view', 'prizes', 'media', 'schedule',
            'winners', 'brackets', 'participants',
        ])) {
            $this->layout = 'tournament';
            $slug = Yii::$app->request->get('slug', null);

            $bracketId = intval(Yii::$app->request->get('id', 0));

            $this->tournament = $this->findModel($slug);

            $this->view->params['tournament'] = $this->tournament;
            $this->view->params['action'] = $action->id;

            if ($action->id == 'brackets') {
                if ($bracketId) {
                    $bracket = BracketFacade::from($bracketId);
                    if (!is_object($bracket)) throw new NotFoundHttpException('Page not found');
                } else {
                    $brackets =  Bracket::findAll(['tournament_id' => $this->tournament->id]);
                    $bracket = count($brackets) ? BracketFacade::from($brackets[0]->id) : null;
                }

                $this->bracket = $bracket;
                $this->view->params['bracket'] = $this->bracket;
            }
        }

        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        if (Yii::$app->request->get('preview', false)) {
            $this->previewTransaction->rollBack();
        }
        return parent::afterAction($action, $result);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'edit', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createTournament'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'edit'],
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
                    [
                        'allow' => true,
                        'actions' => ['delete'],
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'edit' => ['post'],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => ['create', 'update', 'delete']
            ],
        ];
    }

    /**
     * Displays tournaments list.
     *
     * @return mixed
     * @throws \Exception
     */
    public function actionIndex()
    {
        $searchModel = new TournamentSearch();
        $data = Yii::$app->request->get();
        $dataProvider = $searchModel->search($data, '');

        $schedules = $this->scheduleService->getScheduleTournaments();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Displays champions list.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionChampions()
    {
        $searchModel = new ChampionSearch();
        $data = Yii::$app->request->get();
        $dataProvider = $searchModel->search($data, '');

        return $this->render('champions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays tournaments page.
     *
     * @param $slug
     * @return mixed
     */
    public function actionView($slug)
    {
        return $this->render('view', [
            'model' => $this->tournament
        ]);
    }

    /**
     * Displays rules page.
     *
     * @return mixed
     */
    public function actionRules($slug)
    {
        return $this->render('rules', [
            'model' => $this->tournament
        ]);
    }

    /**
     * Displays rules page.
     *
     * @return mixed
     */
    public function actionSchedule($slug)
    {
        return $this->render('schedule', [
            'model' => $this->tournament
        ]);
    }

    /**
     * Displays media page.
     *
     * @return mixed
     */
    public function actionMedia($slug)
    {
        return $this->render('media', [
            'model' => $this->tournament
        ]);
    }

    /**
     * Displays prizes page.
     *
     * @return mixed
     */
    public function actionPrizes($slug)
    {
        return $this->render('prizes', [
            'model' => $this->tournament
        ]);
    }

    /**
     * Displays winners page.
     *
     * @return mixed
     */
    public function actionWinners($slug)
    {
        $teamMode = ($type = $this->tournament->type) ? boolval($type->team_mode) : false;

        return $this->render($teamMode ? 'winners-team' : 'winners', [
            'model' => $this->tournament
        ]);
    }

    /**
     * Displays participants page.
     *
     * @return mixed
     */
    public function actionParticipants($slug)
    {
        $teamMode = ($type = $this->tournament->type) ? boolval($type->team_mode) : false;

        if ($this->tournament->status == Tournament::STATUS_COMPLETED) {
            return $this->actionWinners($slug);
        }

        return $this->render($teamMode ? 'participants-team' : 'participants', [
            'model' => $this->tournament
        ]);
    }

    /**
     * @param $slug
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionBrackets($slug, $id = null)
    {
        $post = Yii::$app->request->post();
        $isTeam = ($type = $this->tournament->type) ? boolval($type->team_mode) : false;

        $canModify = Yii::$app->user->can('updateTournament', ['tournamentId' => $this->tournament->id]);
        if (is_object($this->bracket)) {
            if ($this->bracket->bracket_type == Bracket::TYPE_TABLE) {
                return $this->bracketTable($post, $isTeam, $canModify);
            }
            if ($this->bracket->bracket_type == Bracket::TYPE_RELEGATION) {
                return $this->bracketRelegation($post, $isTeam, $canModify);
            }
            if ($this->bracket->bracket_type == Bracket::TYPE_GROUP) {
                return $this->bracketGroup($post, $isTeam, $canModify);
            }
            if ($this->bracket->bracket_type == Bracket::TYPE_SWISS) {
                return $this->bracketSwiss($post, $isTeam, $canModify);
            }
        }

        return $this->render('_list_empty', ['renderInPlace' => true]);
    }

    /**
     * @param array $post
     * @param bool $isTeam
     * @param bool $canModify
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    private function bracketTable($post, $isTeam, $canModify)
    {
        if ($canModify) {
            if ($isTeam) {
                $model = new BracketTableRowTeamForm(['bracketId' => $this->bracket->id]);
            } else {
                $model = new BracketTableRowForm(['bracketId' => $this->bracket->id]);
            }

            if (Yii::$app->request->isPost) {
                $duelsFailSave = $model->loadForm($post) ? !$model->saveForm() : false;

                if (!$duelsFailSave) {
                    $tournamentId = ArrayHelper::getValue($post, 'Tournament.id', 0);
                    $tournamentPost = $this->findModelById($tournamentId);

                    $tournamentPost->load($post) && $tournamentPost->save();

                    if (!Yii::$app->request->get('preview', false)) {
                        return $this->redirect(['tournament/brackets', 'slug' => $this->tournament->slug, 'id' => $this->bracket->id]);
                    }
                }
            }

            return $this->render($isTeam ? 'bracket/table/bracket-table-team-form' : 'bracket/table/bracket-table-form', [
                'model' => $model,
                'bracket' => $this->bracket,
                'tournament' => $this->tournament,
            ]);
        } else {
            if ($isTeam) {
                $searchModel = new BracketTableRowTeamSearch(['bracketId' => $this->bracket->id]);
            } else {
                $searchModel = new BracketTableRowSearch(['bracketId' => $this->bracket->id]);
            }

            $dataProvider = $searchModel->search(Yii::$app->request->get(), '');

            return $this->render($isTeam ? 'bracket/table/bracket-table-team' : 'bracket/table/bracket-table', [
                'model' => $this->tournament,
                'bracket' => $this->bracket,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * @param array $post
     * @param bool $isTeam
     * @param bool $canModify
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    private function bracketGroup($post, $isTeam, $canModify)
    {
        /* @var Group $bracket */
        $bracket = $this->bracket;

        if ($canModify) {
            $model = new BracketGroupDuelsForm([
                'bracketId' => $bracket->id,
                'duels' => $bracket->getDuels(),
            ]);

            if (Yii::$app->request->isPost) {
                $duelsFailSave = $model->load($post) ? !$model->save() : false;

                if (!$duelsFailSave) {
                    $tournamentId = ArrayHelper::getValue($post, 'Tournament.id', 0);
                    $tournamentPost = $this->findModelById($tournamentId);
                    $tournamentPost->load($post) && $tournamentPost->save();

                    if (!Yii::$app->request->get('preview', false)) {
                        return $this->redirect(Url::to(['tournament/brackets', 'slug' => $this->tournament->slug, 'id' => $this->bracket->id]) . $post['__hash'] ?? '');
                    } else {
                        return $this->render('bracket/group/form', [
                            'canModify' => false,
                            'model' => $model,
                            'bracket' => $bracket,
                            'tournament' => $bracket->tournament,
                        ]);
                    }
                }
            }

            return $this->render('bracket/group/form', [
                'canModify' => $canModify,
                'model' => $model,
                'bracket' => $bracket,
                'tournament' => $bracket->tournament,
            ]);

        } else {
            return $this->render('bracket/group/page', [
                'duels' => $bracket->getDuels(),
                'bracket' => $bracket,
            ]);
        }
    }

    /**
     * @param array $post
     * @param bool $isTeam
     * @param bool $canModify
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    private function bracketRelegation($post, $isTeam, $canModify)
    {
        if ($canModify) {
            $model = new BracketRelegationDuelsForm([
                'bracketId' => $this->bracket->id
            ]);

            if (Yii::$app->request->isPost) {
                $tournamentId = ArrayHelper::getValue($post, 'Tournament.id', 0);
                $tournamentPost = $this->findModelById($tournamentId);
                $tournamentPost->load($post) && $tournamentPost->save();

                $loaded = $model->load([
                    'duels' => $post[$model->formName()]
                ], '');

                if ($loaded && $model->save()) {
                    if (!Yii::$app->request->get('preview', false)) {
                        return $this->redirect(Url::to(['tournament/brackets', 'slug' => $this->tournament->slug, 'id' => $this->bracket->id]) . $post['__hash'] ?? '');
                    }
                }
            }

            return $this->render('bracket/relegation/form', [
                'model' => $model,
                'bracket' => $this->bracket,
                'tournament' => $this->tournament,
            ]);
        } else {
            return $this->render('bracket/relegation/page', [
                'bracket' => $this->bracket,
                'tournament' => $this->tournament,
            ]);
        }
    }

    /**
     * @param array $post
     * @param bool $isTeam
     * @param bool $canModify
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    private function bracketSwiss($post, $isTeam, $canModify)
    {
        /* @var Swiss $bracket */
        $bracket = $this->bracket;

        if ($canModify) {
            $model = new BracketSwissDuelsForm([
                'bracketId' => $bracket->id,
                'duels' => $bracket->getDuels(),
            ]);

            if (Yii::$app->request->isPost) {
                $duelsFailSave = $model->load($post) ? !$model->save() : false;

                if (!$duelsFailSave) {
                    $tournamentId = ArrayHelper::getValue($post, 'Tournament.id', 0);
                    $tournamentPost = $this->findModelById($tournamentId);
                    $tournamentPost->load($post) && $tournamentPost->save();

                    if (!Yii::$app->request->get('preview', false)) {
                        return $this->redirect(Url::to(['tournament/brackets', 'slug' => $this->tournament->slug, 'id' => $this->bracket->id]) . $post['__hash'] ?? '');
                    }

                }
            }

            return $this->render('bracket/swiss/form', [
                'canModify' => $canModify,
                'model' => $model,
                'bracket' => $bracket,
                'tournament' => $bracket->tournament,
            ]);

        } else {

            return $this->render('bracket/swiss/page', [
                'duels' => $bracket->getDuels(),
                'bracket' => $bracket,
            ]);
        }
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = (new TournamentForm())->initDefaultValues();

        if ($model->load(Yii::$app->request->post()) && ($slug = $model->saveTournament())) {
            return $this->redirect(['/tournament/brackets', 'slug' => $slug]);
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findFormById($id);
        // if ($model->status != Tournament::STATUS_PENDING) {
        //     return $this->renderAjax('popup_main-info-denied', [
        //         'model' => $model
        //     ]);
        // }

        if ($model->load(Yii::$app->request->post()) && $model->saveTournament()) {
            $redirectUrl = Yii::$app->request->post('ajaxPopupRedirect', ['cabinet/tournaments']);
            return $this->redirect($redirectUrl);
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    /**
     * Edit action for bottom fixed panel of tournament page
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionEdit()
    {
        $post = Yii::$app->request->post();

        $redirectAction = ArrayHelper::getValue($post, 'action', 'rules');
        $tournamentId = ArrayHelper::getValue($post, 'Tournament.id', 0);

        $model = $this->findModelById($tournamentId);

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
        }

        return $this->redirect(['/tournament/' . $redirectAction, 'slug' => $model->slug]);
    }

    // /**
    //  * @param int|null $id
    //  * @param string|null $place
    //  * @return \yii\web\Response
    //  * @throws \Throwable
    //  */
    // public function actionPreview($id = null, $place = null)
    // {
    //     $id = (integer) Yii::$app->request->get('id', 0);
    //     $place = rawurldecode(Yii::$app->request->get('place', ''));
    //
    //     if ($clone === false || !$clone->id) throw new NotFoundHttpException();
    //
    //     if (!$place) $place = '/brackets';
    //
    //     return $this->redirect(Url::to(['/tournament/brackets', 'slug' => $clone->slug]) . $place);
    // }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $model = $this->findModelById($id);

        if (Yii::$app->request->isPost) {
            $model->delete();
            return $this->redirect(['cabinet/tournaments']);
        }

        return $this->renderAjax('delete', [
            'model' => $model
        ]);
    }
	
    public function actionExport($slug)
    {
	$tournament = $this->findModel($slug);
	(new \frontend\service\export\TournamentExport())->run($tournament);
	
	die();
    }

    /**
     * @param $slug
     * @return Tournament
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    protected function findModel($slug)
    {
        if (!$slug) {
            throw new BadRequestHttpException();
        }

        $model = $this->tournamentService->getTournament($slug);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tournament not found');
    }

    /**
     * @param $id int
     * @return Bracket
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    protected function findBracketById($id)
    {
        if (!$id) {
            throw new BadRequestHttpException();
        }

        $model = Bracket::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Bracket not found');
    }

    /**
     * @param $id
     * @return Tournament
     * @throws NotFoundHttpException
     */
    protected function findModelById($id)
    {
        $model = $this->tournamentService->getTournamentById($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tournament not found');
    }

    /**
     * @param $id
     * @return TournamentForm
     * @throws NotFoundHttpException
     */
    protected function findFormById($id)
    {
        $model = TournamentForm::findOne($id);
        $model->scenario = Yii::$app->user->can('organizer')
            ? TournamentForm::SCENARIO_ORGANIZER : TournamentForm::SCENARIO_ADMIN;

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tournament not found');
    }
}
