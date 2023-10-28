<?php

namespace frontend\controllers;

use Yii;
use frontend\models\TournamentType;
use common\services\TournamentService;
use frontend\models\Tournament;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /** @var Tournament */
    private $tournament;

    /**
     * @var TournamentService
     */
    private $tournamentService;

    /**
     * TournamentController constructor.
     * @param $id
     * @param $module
     * @param TournamentService $tournamentService
     * @param array $config
     */
    public function __construct($id, $module, TournamentService $tournamentService, $config = [])
    {
        $this->tournamentService = $tournamentService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $championsRelations = $this->tournamentService->getChampionsRelations(7, 0, [
            'tour.status = ' . Tournament::STATUS_COMPLETED
        ]);

        return $this->render('index', [
            'tournamentTypes' => TournamentType::getTypesForHome(),
            'championsRelations' => $championsRelations,
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionThanks()
    {
        $nicksString = ArrayHelper::getValue(Yii::$app->params, 'settings.thanks_nicks', '');
        $nicksArray = [];
        foreach (explode("\n", $nicksString) as $nick) {
            if (!trim($nick)) continue;

            $nicksArray[] = $nick;
        }

        return $this->render('thanks', [
            'nicks' => $nicksArray,
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionDonate()
    {
        return $this->render('donate');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionContacts()
    {
        return $this->render('contacts');
    }

    public function actionStatick()
    {
        return $this->render('statick');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionTerms()
    {
        return $this->render('terms');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionPrivacy()
    {
        return $this->render('privacy');
    }

    public function actionTest()
    {
        return $this->renderPartial('test');
    }

}
