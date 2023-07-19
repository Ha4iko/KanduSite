<?php

namespace frontend\widgets;

use common\widgets\BaseWidget;
use common\services\TournamentService;
use frontend\models\Tournament;

class LatestTournamentsWidget extends BaseWidget
{
    /**
     * @var int
     */
    public $limit = 6;
    public $noPending = false;

    /**
     * @var array
     */
    public $excludeIds = [];

    /**
     * @var $tournamentService TournamentService
     */
    private $tournamentService;

    /**
     * LatestTournamentsWidget constructor.
     * @param TournamentService $tournamentService
     * @param array $config
     */
    public function __construct(TournamentService $tournamentService, $config = [])
    {
        $this->tournamentService = $tournamentService;
        parent::__construct($config);
    }

    /**
     * init widget
     */
    public function init()
    {
       parent::init();

       if (!$this->limit) $this->limit = 1;
       if ($this->limit < 1) $this->limit = 1;
       if ($this->limit > 100) $this->limit = 100;
    }

    /**
     * @return string
     */
    public function run()
    {
        $conditions = $this->noPending
            ? ['status' => [Tournament::STATUS_IN_PROGRESS, Tournament::STATUS_COMPLETED]]
            : [];

        $tournaments = $this->tournamentService->getLatestTournaments($this->limit, $this->excludeIds, $conditions);

        return $this->render('list', [
            'tournaments' => $tournaments,
        ]);
    }
}

