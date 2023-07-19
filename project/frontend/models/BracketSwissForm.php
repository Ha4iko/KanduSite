<?php

namespace frontend\models;

use common\services\Bracket\SwissService;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class BracketSwissForm extends Bracket
{
    /**
     * @var SwissService
     */
    private $swissService;

    /**
     * @throws InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        parent::init();

        $this->swissService = Yii::$container->get(SwissService::class);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['participants', 'best_of', 'title', 'bracket_type'], 'required'],
            [['participants'], 'integer', 'min' => 5, 'max' => 256],
            [['round_count'], 'integer', 'min' => 1, 'max' => 10],
            [['round_count'], 'roundCount'],
            [['best_of'], 'integer', 'min' => 1, 'max' => 5],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function roundCount()
    {
        if (
            ( ($this->participants >= 5 && $this->participants <= 8)
              && !($this->round_count >= 1 && $this->round_count <= 5) )

            || ( ($this->participants >= 9 && $this->participants <= 16)
              && !($this->round_count >= 1 && $this->round_count <= 6) )

            || ( ($this->participants >= 17 && $this->participants <= 32)
              && !($this->round_count >= 1 && $this->round_count <= 7) )

            || ( ($this->participants >= 33 && $this->participants <= 64)
              && !($this->round_count >= 1 && $this->round_count <= 8) )

            || ( ($this->participants >= 65 && $this->participants <= 128)
              && !($this->round_count >= 1 && $this->round_count <= 9) )

            || ( ($this->participants >= 129 && $this->participants <= 256)
              && !($this->round_count >= 1 && $this->round_count <= 10) )
        ) {
            $this->addError('round_count', 'Invalid number of rounds for the specified number of participants');
        }
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
            $this->bracket_type = Bracket::TYPE_SWISS;
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
                    $this->swissService->clearAll($this->id);
                    $this->swissService->create(
                        $this->id,
                        $this->participants,
                        $this->tournament->type->team_mode,
                        $this->round_count
                    );
                }
                if ($isNewRecord && !$clone) {
                    $this->swissService->create(
                        $this->id,
                        $this->participants,
                        $this->tournament->type->team_mode,
                        $this->round_count
                    );
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
