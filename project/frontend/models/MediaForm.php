<?php
namespace frontend\models;

use common\traits\ImageTrait;
use Yii;
use yii\helpers\ArrayHelper;

class MediaForm extends Media
{
    use ImageTrait;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'date'], 'required'],
            [['is_text', 'is_video', 'active'], 'integer'],
            [['title', 'bg_image', 'slug'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['published'], 'date', 'format' => 'php:m/d/Y'],
        ];
    }

    /**
     * @return $this
     * @throws \yii\base\InvalidConfigException
     */
    public function initDefaultValues()
    {
        $this->date = Yii::$app->formatter->asDate('now', 'php:Y-m-d');
        $this->active = 1;
        return $this;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Throwable
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->is_video = $this->checkboxToInteger('is_video');
        $this->is_text = $this->checkboxToInteger('is_text');
        $this->active = $this->checkboxToInteger('active');

        if (!$this->is_text && !$this->is_video) {
            $this->addError('is_text', 'Choose at least 1 type');
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @param string $checkboxAttribute
     * @return int
     * @throws \Throwable
     */
    private function checkboxToInteger(string $checkboxAttribute)
    {
        $data = ArrayHelper::getValue(Yii::$app->request->post(), $this->formName(), []);
        return key_exists($checkboxAttribute, $data)
            ? ($data[$checkboxAttribute] === 'on' ? 1 : 0)
            : 0;
    }
}