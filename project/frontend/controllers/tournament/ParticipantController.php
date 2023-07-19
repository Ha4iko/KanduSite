<?php

namespace frontend\controllers\tournament;

use common\models\PlayerWorld;
use common\services\ParserService;
use frontend\models\ParticipantsWithTeamsForm;
use frontend\models\Player;
use frontend\models\Tournament;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Participants controller
 */
class ParticipantController extends Controller
{
    /**
     * @var ParserService
     */
    private $parserService;

    /**
     * TournamentController constructor.
     * @param $id
     * @param $module
     * @param ParserService $parserService
     * @param array $config
     */
    public function __construct($id, $module, ParserService $parserService, $config = [])
    {
        $this->parserService = $parserService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setViewPath('@app/views/_tournament/participant');

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
                'only' => ['update', 'update-team', 'parse-player'],
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
                    [
                        'allow' => true,
                        'actions' => ['parse-player'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => ['update', 'update-team', 'parse-player']
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
        unset($post['TournamentToPlayer']['%i%']);

        if (Yii::$app->request->isPost) {
            $this->createPlayersIfNotExist($post['TournamentToPlayer'] ?? []);
            $this->createWorldsIfNotExist($post['TournamentToPlayer'] ?? []);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['tournament/participants', 'slug' => $model->slug]);
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
    public function actionUpdateTeam($id)
    {
        $model = $this->findFormById($id);

        $post = Yii::$app->request->post();

        if (Yii::$app->request->isPost) {
            $model->prepareData($post);
        }

        if ($model->load($post)) {
            try {
                if ($saved = $model->save()) {
                    return $this->redirect(['tournament/participants', 'slug' => $model->slug]);
                }
            } catch (\Exception $e) {
                throw new HttpException(500, 'Participants edition is failed');
            }
        }

        return $this->renderAjax('update-team', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionParsePlayer()
    {
        $url = Yii::$app->request->post('url');
        if (!$url) return $this->asJson(['status' => 'fail', 'message' => 'Incorrect url for parsing player data']);

        $player = $this->parserService->parsePlayer($url, ParserService::TARGET_WOW_COM);
        if ($player === false) {
            return $this->asJson(['status' => 'fail', 'message' => 'Incorrect url for parsing player data']);
        } else {
            if ($playerModel = Player::findOne(['nick' => $player['nick']])) {
                $playerModel->external_link = $url;
                $playerModel->loadAvatar();
                $playerModel->save();
            } else {
                $playerModel = new Player();
                $playerModel->nick = $player['nick'];
                $playerModel->external_link = $url;
                $playerModel->loadAvatar();
                $playerModel->save();
            }
        }

        return $this->asJson([
            'status' => 'success',
            'player' => $player,
            'worlds' => PlayerWorld::find()->asArray()->orderBy('name')
                ->select('name, id')
                //->indexBy('id')
                ->column(),
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

    /**
     * @param $id
     * @return ParticipantsWithTeamsForm
     * @throws NotFoundHttpException
     */
    protected function findFormById($id)
    {
        $model = ParticipantsWithTeamsForm::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tournament not found');
    }

    /**
     * @param array $postLinks
     * @throws Exception
     */
    protected function createPlayersIfNotExist(array $postLinks)
    {
        foreach ($postLinks as $postLink) {
            $nick = trim($postLink['playerNick'] ?? '');
            if (!$nick) continue;

            if (null === Player::findOne(['nick' => $nick])) {
                $player = new Player();
                $player->loadDefaultValues();
                $player->nick = $nick;
                $player->loadAvatar();
                if (!$player->save()) {
                    throw new Exception('Creation player is failed');
                }
            }
        }
    }

    /**
     * @param array $postLinks
     * @throws Exception
     */
    protected function createWorldsIfNotExist(array $postLinks)
    {
        foreach ($postLinks as $postLink) {
            $name = trim($postLink['worldName'] ?? '');
            if (!$name) continue;

            if (null === PlayerWorld::findOne(['name' => $name])) {
                $world = new PlayerWorld();
                $world->loadDefaultValues();
                $world->name = $name;
                if (!$world->save()) {
                    throw new Exception('Creation world is failed');
                }
            }
        }
    }
}

