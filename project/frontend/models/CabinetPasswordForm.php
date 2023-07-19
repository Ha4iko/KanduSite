<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Cabinet password form
 *
 * @property integer $id
 * @property string $password
 * @property string $password_old
 * @property string $password_confirm
 */
class CabinetPasswordForm extends Model
{
    public $id;
    public $password;
    public $password_old;
    public $password_confirm;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_old', 'password_confirm'], 'required'],
            [['password', 'password_old', 'password_confirm'], 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['password', 'validatePasswordNew'],
            ['password_old', 'validatePasswordOld'],
            ['password_confirm', 'validatePasswordConfirm'],
        ];
    }

    public function validatePasswordConfirm($attribute, $params)
    {
        if ($this->$attribute != $this->password) {
            $this->addError($attribute, 'Password mismatch');
        }
    }

    public function validatePasswordOld($attribute, $params)
    {
        if ($this->id) {
            $user = User::findIdentity($this->id);
            if ($user && !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Incorrect old password');
            }
        }
    }

    public function validatePasswordNew($attribute, $params)
    {
        if ($this->id) {
            $user = User::findIdentity($this->id);
            if ($user && $user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'The new password is the same as the current one');
            }
        }
    }

    /**
     * Returns the attribute labels.
     *
     * @return array attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return [
            'password_old' => 'Old password',
            'password' => 'New password',
            'password_confirm' => 'Confirm new password',
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
            $this->addError('password_old', 'User not found.');
            return false;
        }

        $user->setPassword($this->password);
        // code for future (create user)
        // $user->generateAuthKey();
        // $user->generateEmailVerificationToken();
        $saved = $user->save();

        if ($saved) {
            $this->password = '';
            $this->password_old = '';
            $this->password_confirm = '';
        }

        return $saved;
    }

}
