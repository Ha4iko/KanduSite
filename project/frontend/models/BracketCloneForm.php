<?php

namespace frontend\models;

use common\models\Bracket\Group\Round as GroupRound;
use common\models\Bracket\Group\Group as GroupGroup;
use common\models\Bracket\Swiss\Round as SwissRound;
use common\models\Bracket\Group\PlayerDuel as GroupPlayer;
use common\models\Bracket\Group\TeamDuel as GroupTeam;
use common\models\Bracket\Relegation;
use common\models\Bracket\Swiss;
use common\services\Bracket\RelegationService;
use common\services\Bracket\SwissService;
use Yii;
use yii\base\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $old_title
 */
class BracketCloneForm extends Model
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $old_title;

    /**
     * @var RelegationService
     */
    private $relegationService;

    /**
     * @var SwissService
     */
    private $swissService;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        parent::init();
        $this->relegationService = Yii::$container->get(RelegationService::class);
        $this->swissService = Yii::$container->get(SwissService::class);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'New name',
        ];
    }

    /**
     * @param int $bracketId
     * @param int $bracketTypeId
     * @return int new bracket id if success
     * @throws \Throwable
     */
    public function clone($bracketId, $bracketTypeId)
    {
        if (!$this->validate()) {
            return 0;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $bracketForm = $this->findBracketForm($bracketId, $bracketTypeId);
            $bracketForm->setIsNewRecord(true);
            $bracketForm->id = null;
            $bracketForm->title = $this->title;
            $saved = $bracketForm->saveForm(true);

            if ($saved) {
                $bracketFormSource = $this->findBracketForm($bracketId, $bracketTypeId);
                $this->cloneInnerEntities($bracketForm, $bracketFormSource, $bracketTypeId);
            }

            $transaction->commit();
            return $saved ? $bracketForm->id : 0;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


    /**
     * @param $bracketForm
     * @param $bracketFormSource
     * @param $typeId
     * @throws \Throwable
     */
    protected function cloneInnerEntities($bracketForm, $bracketFormSource, $typeId)
    {
        switch ($typeId) {
            case Bracket::TYPE_GROUP:
                $this->cloneGroupEntities($bracketForm, $bracketFormSource);
                break;
            case Bracket::TYPE_SWISS:
                $this->cloneSwissEntities($bracketForm, $bracketFormSource);
                break;
            case Bracket::TYPE_RELEGATION:
                $this->cloneRelegationEntities($bracketForm, $bracketFormSource);
                break;
            case Bracket::TYPE_TABLE:
                $this->cloneTableEntities($bracketForm, $bracketFormSource);
                break;
            default:
                throw new \Exception('Unknown clone case');
        }
    }

    /**
     * @param $id
     * @param $typeId
     * @return BracketGroupForm|BracketSwissForm|BracketRelegationForm|BracketTableForm
     * @throws \Throwable
     */
    protected function findBracketForm($id, $typeId)
    {
        if ($typeId == Bracket::TYPE_GROUP) {
            $bracketFormClass = BracketGroupForm::class;
        } elseif ($typeId == Bracket::TYPE_SWISS) {
            $bracketFormClass = BracketSwissForm::class;
        } elseif ($typeId == Bracket::TYPE_RELEGATION) {
            $bracketFormClass = BracketRelegationForm::class;
        } elseif ($typeId == Bracket::TYPE_TABLE) {
            $bracketFormClass = BracketTableForm::class;
        } else {
            throw new \Exception('BracketForm not found');
        }

        $model = $bracketFormClass::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new \Exception('BracketForm not found');
    }

    /**
     * @param BracketGroupForm $bracketForm
     * @param BracketGroupForm $bracketFormSource
     * @throws \Exception
     */
    private function cloneGroupEntities(BracketGroupForm $bracketForm, BracketGroupForm $bracketFormSource) {
        $roundNewIds = [];
        foreach (GroupRound::findAll(['bracket_id' => $bracketFormSource->id]) as $round) {
            $oldId = $round->id;
            $round->setIsNewRecord(true);
            $round->id = null;
            $round->bracket_id = $bracketForm->id;
            if (!$round->save()) {
                throw new \Exception('Round not saved');
            }
            $roundNewIds[$oldId] = $round->id;
        }


        $groupNewIds = [];
        foreach (GroupGroup::findAll(['bracket_id' => $bracketFormSource->id]) as $group) {
            $oldId = $group->id;
            $group->setIsNewRecord(true);
            $group->id = null;
            $group->bracket_id = $bracketForm->id;
            if (!$group->save()) {
                throw new \Exception('Group not saved');
            }
            $groupNewIds[$oldId] = $group->id;
        }

        foreach (GroupRound::findAll(['bracket_id' => $bracketFormSource->id]) as $round) {
            foreach ($round->getDuels(true) as $duel) {
                $oldRoundId = $duel->round_id;
                $oldGroupId = $duel->group_id;
                $duel->setIsNewRecord(true);
                $duel->id = null;
                $duel->round_id = $roundNewIds[$oldRoundId];
                $duel->group_id = $groupNewIds[$oldGroupId];
                $duel->score_one = null;
                $duel->score_two = null;
                $duel->winner_id = null;
                $duel->loser_id = null;
                $duel->active = 1;
                if (!$duel->save()) {
                    throw new \Exception('Duel not saved');
                }
            }
        }
    }

    /**
     * @param BracketTableForm $bracketForm
     * @param BracketTableForm $bracketFormSource
     * @throws \Exception
     */
    private function cloneTableEntities(BracketTableForm $bracketForm, BracketTableForm $bracketFormSource) {
        foreach ($bracketFormSource->bracketTableColumns as $column) {
            $column->setIsNewRecord(true);
            $column->id = null;
            $column->bracket_id = $bracketForm->id;
            if (!$column->save()) {
                throw new \Exception('Column not saved');
            }
        }
        foreach ($bracketFormSource->bracketTableRows as $row) {
            $row->setIsNewRecord(true);
            $row->id = null;
            $row->bracket_id = $bracketForm->id;
            if (!$row->save()) {
                throw new \Exception('Row not saved');
            }
        }
        foreach ($bracketFormSource->bracketTableRowsTeam as $row) {
            $row->setIsNewRecord(true);
            $row->id = null;
            $row->bracket_id = $bracketForm->id;
            if (!$row->save()) {
                throw new \Exception('Row not saved');
            }
        }
    }

    /**
     * @param BracketRelegationForm $bracketForm
     * @param BracketRelegationForm $bracketFormSource
     * @throws \Exception
     */
    private function cloneRelegationEntities(BracketRelegationForm $bracketForm, BracketRelegationForm $bracketFormSource) {
        $firstRoundParticipants = [];
        $bracket = Relegation::findOne($bracketFormSource->id);
        $duels = $bracket->getDuels(Relegation\Round::TYPE_MAIN, 1);
        foreach ($duels as $duel) {
            $firstRoundParticipants[] = $duel->player_1;
            $firstRoundParticipants[] = $duel->player_2;
        }
        $this->relegationService->fillFirstRound($bracketForm->id, $firstRoundParticipants);
    }

    /**
     * @param BracketSwissForm $bracketForm
     * @param BracketSwissForm $bracketFormSource
     * @throws \Exception
     */
    private function cloneSwissEntities(BracketSwissForm $bracketForm, BracketSwissForm $bracketFormSource)
    {
        $isTeam = $bracketForm->tournament->type->team_mode;
        $bracketForm->editable = 1;
        $bracketForm->editable_participants = 1;
        $bracketForm->editable_scores = 1;
        if (!$bracketForm->save()) {
            throw new \Exception('Bracket not saved');
        }

        $roundNewIds = [];
        foreach (SwissRound::findAll(['bracket_id' => $bracketFormSource->id]) as $round) {
            $oldId = $round->id;
            $round->setIsNewRecord(true);
            $round->id = null;
            $round->bracket_id = $bracketForm->id;
            if (!$round->save()) {
                throw new \Exception('Round not saved');
            }
            $roundNewIds[$oldId] = $round->id;
        }

        foreach (SwissRound::findAll(['bracket_id' => $bracketFormSource->id]) as $round) {
            foreach ($round->getDuels(true) as $duel) {
                $isFirstRound = $duel->round->order == 1;

                $oldRoundId = $duel->round_id;
                $duel->setIsNewRecord(true);
                $duel->id = null;
                $duel->round_id = $roundNewIds[$oldRoundId];
                $duel->score_one = null;
                $duel->score_two = null;
                $duel->winner_id = null;
                $duel->loser_id = null;
                if (!$isFirstRound) {
                    if ($isTeam) {
                        $duel->team_one_id = null;
                        $duel->team_two_id = null;
                    } else {
                        $duel->player_one_id = null;
                        $duel->player_two_id = null;
                    }
                }
                $duel->active = 1;
                if (!$duel->save()) {
                    throw new \Exception('Duel not saved');
                }
            }
        }
    }
}
