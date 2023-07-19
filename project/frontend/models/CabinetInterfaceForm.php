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
 * @property boolean $time_zone
 * @property boolean $language_id
 */
class CabinetInterfaceForm extends Model
{
    public $id;
    public $time_zone;
    public $language_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time_zone', 'language_id'], 'integer'],
            [['time_zone', 'language_id'], 'required'],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return [
            'time_zone' => 'Time zone',
            'language_id' => 'Language',
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
        $this->time_zone = $user->time_zone;
        $this->language_id = $user->language_id;

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

        $user->time_zone = $this->time_zone;
        $user->language_id = $this->language_id;
        $saved = $user->save();

        return $saved;
    }

}
