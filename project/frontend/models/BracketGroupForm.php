<?php

namespace frontend\models;

use common\services\Bracket\GroupService;
use Yii;
use yii\base\InvalidConfigException;

class BracketGroupForm extends Bracket
{
    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * @throws InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        parent::init();

        $this->groupService = Yii::$container->get(GroupService::class);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['participants', 'best_of', 'group_count', 'title'], 'required'],
            [['participants'], 'integer', 'min' => 3, 'max' => 14],
            [['group_count'], 'integer', 'min' => 1, 'max' => 16],
            [['best_of'], 'integer', 'min' => 1, 'max' => 5],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @param array $data
     * @param int $tournament_id
     * @return bool
     * @throws InvalidConfigException
     */
    public function loadData(array $data, int $tournament_id)
    {
        $loaded = parent::load($data);

        if ($loaded) {
            $this->tournament_id = $tournament_id;
            $this->bracket_type = Bracket::TYPE_GROUP;
            $this->order = 0;
        }

        return $loaded;
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function saveForm($clone = false)
    {
        if (!$this->validate()) {
            return false;
        }
        $isNewRecord = $this->isNewRecord;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = $this->save();
            if ($saved) {
                if (!$isNewRecord && $this->editable) {
                    $this->groupService->clearAll($this->id);
                    $this->groupService->createDuels($this->id, $this->participants, $this->group_count);
                }
                if ($isNewRecord && !$clone) {
                    $this->groupService->createDuels($this->id, $this->participants, $this->group_count);
                }
            } else {
                throw new \Exception('Form not saved');
            }
            $transaction->commit();
            return $saved;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }



}
