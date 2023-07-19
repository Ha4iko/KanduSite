<?php

namespace frontend\models;

use common\models\Bracket\Group\Duel;
use common\models\Bracket\Group;
use common\services\Bracket\GroupService;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property int $bracketId
 * @property Duel $duels
 *
 * @property array $bracketParticipantsIds
 * @property Group $bracket
 */
class BracketGroupDuelsForm extends Model
{
    /**
     * @var int
     */
    public $bracketId;

    /**
     * @var Duel
     */
    public $duels = [];

    /**
     * @var Group
     */
    public $bracket;

    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * @var array
     */
    private $changedDuelIds = [];

    /**
     * @throws \Throwable
     */
    public function init()
    {
        parent::init();

        $this->groupService = Yii::$container->get(GroupService::class);
        $this->bracket = Group::findOne($this->bracketId);
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @param array $data
     * @param string $formName
     * @return bool
     * @throws \Throwable
     */
    public function load($data, $formName = null)
    {
        if (!Yii::$app->request->isPost) return false;

        $duelsPost = ArrayHelper::index(ArrayHelper::getValue($data, 'Duels', []), 'id');

        if (empty($duelsPost)) return false;

        $this->changedDuelIds = [];

        foreach ($this->duels as &$duel) {
            if (isset($duelsPost[$duel->id])) {
                $this->loadDuelAttributes($duel,
                    ['player_1', 'player_2', 'score_one', 'score_two'],
                    $duelsPost[$duel->id]
                );
            }
        }

        return !empty($this->changedDuelIds);
    }


    public function validate($attributeNames = null, $clearErrors = true)
    {
        $modifiedDuelsIsValid = true;

        foreach ($this->duels as &$duel) {
            if (!isset($this->changedDuelIds[$duel->id])) continue;

            if (!$duel->validate()) {
                $modifiedDuelsIsValid = false;
            }
        }

        return $modifiedDuelsIsValid;
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        foreach ($this->duels as $duel) {
            if (!isset($this->changedDuelIds[$duel->id])) continue;

            if (!$duel->save()) {
                throw new \Exception('Duel not saved: ' . json_encode($duel->getFirstErrors()));
            }
        }

        $this->changedDuelIds = [];

        if ($this->bracket->editable_participants) {
            if ($this->bracket->isManualParticipantsCompleted()) {
                $this->groupService->fillAutoParticipantsInBracket($this->bracket->id);
            } else {
                $this->groupService->clearAutoParticipantsInBracket($this->bracket->id);
            }
        }

        if ($this->bracket->editable_participants && $this->bracket->getCompletedDuelsCount()) {
            $this->bracket->editable_participants = 0;
            if (!$this->bracket->save()) {
                throw new \Exception('Bracket not saved');
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getParticipantsNames()
    {
        return ArrayHelper::merge(
            [null => ''],
            $this->groupService->getBracketParticipantsList($this->bracket->id)
        );
    }

    /**
     * @param Duel $duel
     * @param array $attributes
     * @param array $data
     * @return bool
     */
    private function loadDuelAttributes(Duel $duel, array $attributes, array $data)
    {
        $loaded = false;
        foreach ($attributes as $attribute) {
            if (!key_exists($attribute, $data)) continue;
            $newValue = is_numeric($data[$attribute]) ? intval($data[$attribute]) : null;

            if (in_array($attribute, ['player_1', 'player_2']) && $newValue === 0) $newValue = null;

            if ($duel->{$attribute} !== $newValue) {
                $duel->{$attribute} = $newValue;
                $this->changedDuelIds[$duel->id] = $duel->id;
                $loaded = true;
            }
        }

        return $loaded;
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
}
