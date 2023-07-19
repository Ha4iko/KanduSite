<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * Schedule form
 * @property boolean|string $mark_type
 * @property boolean|string $mark_page
 * @property boolean|string $mark_primary
 */
class ScheduleForm extends Tournament
{
    public $mark_type;
    public $mark_page;
    public $mark_primary;

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
            [['mark_type', 'mark_page', 'mark_primary'], 'normalizeMark'],
        ]);

        return $rules;
    }

    /**
     * Normalize attributes with mark-logic
     *
     * @param $attribute
     * @param $params
     * @throws \yii\base\InvalidConfigException
     */
    public function normalizeMark($attribute, $params)
    {
        $post = Yii::$app->request->post();
        $postValue = ArrayHelper::getValue($post, $this->formName() . '.' . $attribute, false);
        $this->$attribute = (strtolower($postValue) == 'on') || boolval($postValue);
    }

    public function initData() {
        $this->mark_type = $this->schedule_type;
        $this->mark_page = $this->show_on_main_page;
        $this->mark_primary = $this->is_primary;
        return $this;
    }

    public function loadData() {
        $this->schedule_type = intval($this->mark_type);
        $this->show_on_main_page = intval($this->mark_page);
        $this->is_primary = intval($this->mark_primary);
        return true;
    }

}
