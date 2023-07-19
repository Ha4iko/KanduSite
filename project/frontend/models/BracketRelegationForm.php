<?php

namespace frontend\models;

use Yii;
use yii\base\InvalidConfigException;
use common\services\Bracket\RelegationService;

/**
 * Class BracketRelegationForm
 * @package frontend\models
 */
class BracketRelegationForm extends Bracket
{
    /**
     * @var RelegationService
     */
    private $relegationService;

    /**
     * @throws InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        $this->relegationService = Yii::$container->get(RelegationService::class);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['participants', 'best_of'], 'required'],
                [['third_place'], 'boolean'],
                ['participants', 'in', 'range' => [4, 8, 16, 32, 64]],
                ['best_of', 'in', 'range' => [1, 3, 5]]
            ]
        );
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function saveForm($clone = false)
    {
        $this->bracket_type = Bracket::TYPE_RELEGATION;

        if (!$this->validate()) {
            return false;
        }

        $isNewRecord = $this->isNewRecord;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $saved = $this->save();
            if ($saved) {
                if (!$isNewRecord && $this->editable) {
                    $this->relegationService->clearAll($this->id);
                    $this->relegationService->create(
                        $this->id,
                        $this->participants,
                        $this->tournament->type->team_mode,
                        $this->second_defeat,
                        $this->third_place && !$this->second_defeat
                    );
                }
                if ($isNewRecord) {
                    $this->relegationService->create(
                        $this->id,
                        $this->participants,
                        $this->tournament->type->team_mode,
                        $this->second_defeat,
                        $this->third_place && !$this->second_defeat
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
