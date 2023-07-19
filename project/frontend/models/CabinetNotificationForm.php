<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * Cabinet notification form
 *
 * @property integer $id
 * @property boolean $mark_courses
 * @property boolean $mark_discounts
 * @property boolean $mark_certificate
 */
class CabinetNotificationForm extends Model
{
    public $id;
    public $mark_courses;
    public $mark_discounts;
    public $mark_certificate;

    public static $markLabels = [
        'courses' => 'New courses on the portal',
        'discounts' => 'Discounts and sales',
        'certificate' => 'Received certificate',
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mark_courses', 'mark_discounts', 'mark_certificate'], 'normalizeMark'],
        ];
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

    /**
     * Returns the attribute labels.
     *
     * @return array attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return [
            'mark_courses' => static::$markLabels['courses'],
            'mark_discounts' => static::$markLabels['discounts'],
            'mark_certificate' => static::$markLabels['certificate'],
        ];
    }

    /**
     * Load user info.
     *
     * @param string $userId the attribute for searching user
     * @return bool whether the loading user was successful
     */
    public function loadFromUserModel($userId)
    {
        $user = User::findIdentity($userId);
        if (!is_object($user)) {
            return false;
        }

        $this->id = $user->id;

        $marks = explode(',', $user->marks);
        $this->mark_courses = in_array('courses', $marks);
        $this->mark_discounts = in_array('discounts', $marks);
        $this->mark_certificate = in_array('certificate', $marks);

        return true;
    }

    /**
     * Save user info.
     *
     * @return bool whether the saving new account was successful
     */
    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = User::findIdentity($this->id);
        if (!is_object($user)) {
            $this->addError('mark_courses', 'User not found.');
            return false;
        }

        $marks = [];
        if ($this->mark_courses) $marks[] = 'courses';
        if ($this->mark_discounts) $marks[] = 'discounts';
        if ($this->mark_certificate) $marks[] = 'certificate';

        $user->marks = $marks ? implode(',', $marks) : null;
        $saved = $user->save();

        return $saved;
    }

}
