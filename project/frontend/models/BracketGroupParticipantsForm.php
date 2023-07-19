<?php

namespace frontend\models;

use common\models\Bracket\Group as GroupBracket;
use common\services\Bracket\GroupService;
use common\services\TournamentService;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property int $bracketId
 * @property boolean $distributeByClass
 *
 * @property array $attachedParticipantsIds
 * @property array $tournamentParticipants
 * @property Tournament $tournament
 * @property GroupBracket $bracket
 */
class BracketGroupParticipantsForm extends Model
{
    /**
     * @var int
     */
    public $bracketId;

    /**
     * @var Bracket
     */
    public $bracket;

    /**
     * @var Tournament
     */
    public $tournament;

    /**
     * @var array
     */
    public $participants = [];

    /**
     * @var boolean
     */
    public $distributeByClass = false;

    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * @var TournamentService
     */
    private $tournamentService;

    /**
     * @throws InvalidConfigException
     * @throws \Throwable
     */
    public function init()
    {
        parent::init();

        if ($this->bracketId) {
            $this->bracket = GroupBracket::findOne([
                'id' => $this->bracketId,
                'bracket_type' => Bracket::TYPE_GROUP,
            ]);
            if (!is_object($this->bracket)) throw new InvalidConfigException('Bracket not found.');

            $this->tournament = $this->bracket->tournament;
        } else {
            throw new InvalidConfigException('Bracket id expected.');
        }

        $this->groupService = Yii::$container->get(GroupService::class);
        $this->tournamentService = Yii::$container->get(TournamentService::class);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['participants'], 'validateParticipants'],
            [['distributeByClass'], 'safe'],
        ];
    }

    /**
     * Validate scores
     */
    public function validateParticipants() {
        // $totalExpected = $this->bracket->participants * $this->bracket->group_count;
        // if (count($this->participants) != $totalExpected) {
        //     $this->addError('participants', 'Total participants must be ' . $totalExpected);
        // }
    }

    /**
     * @param $data
     * @param null $formName
     * @return bool
     * @throws \Exception
     */
    public function loadForm($data, $formName = null)
    {
        if (!Yii::$app->request->isPost) return false;

        $data = $this->prepareData($data);

        $distributeByClassPost = $data[$this->formName()]['distributeByClass'] ?? null;
        $this->distributeByClass = $distributeByClassPost === 'on';

        $this->participants = ArrayHelper::getValue($data, 'Participant', []);

        return true;
    }

    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data)
    {
        $classNameInData = 'Participant';
        $dataModels = $data[$classNameInData] ?? [];
        foreach ($dataModels as $k => &$dataModel) {
            if (!(isset($dataModel['active']) && $dataModel['active'] === 'on')) {
                unset($dataModels[$k]);
            }
        }
        $data[$classNameInData] = $dataModels;

        return $data;
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function saveForm()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->groupService->attachParticipantsToBracket(
                $this->bracket->id,
                ArrayHelper::map($this->participants, 'id', 'id'),
                $this->distributeByClass
            );

            $this->bracket->editable = 0;
            $this->bracket->save();

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


    /**
     * @return array
     */
    public function getAttachedParticipantsIds()
    {
        $duels = $this->bracket->getDuels();


        $attachedIds = [];
        foreach ($duels as $duel) {
            if ($duel->player_1)
                $attachedIds[$duel->player_1] = $duel->player_1;

            if ($duel->player_2)
                $attachedIds[$duel->player_2] = $duel->player_2;
        }
        return $attachedIds;
    }


    /**
     * @return TournamentToPlayer[]|TournamentToTeam[]
     * @throws \Throwable
     */
    public function getTournamentParticipants()
    {
        return $this->tournamentService->getParticipantsForBracket($this->tournament->id);
    }

}
