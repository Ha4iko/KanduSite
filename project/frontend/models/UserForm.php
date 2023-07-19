<?php
namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property string $user_role
 * @property string $user_name
 * @property string $user_email
 * @property string $user_password
 */
class UserForm extends User
{
    /**
     * @var string
     */
    public $user_role;

    /**
     * @var string
     */
    public $user_name;

    /**
     * @var string
     */
    public $user_email;

    /**
     * @var string
     */
    public $user_password = '';

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['user_role', 'user_name', 'user_password'], 'string'];
        $rules[] = [['user_role', 'user_name', 'user_email'], 'required'];
        $rules[] = [['user_email'], 'email'];
        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['user_role'] = 'Role';
        $labels['user_name'] = 'Nickname';
        $labels['user_email'] = 'E-mail';
        $labels['user_password'] = 'Password';
        return $labels;
    }

    /**
     * @return $this
     */
    public function initDefaultValues()
    {
        $this->user_role = $this->getUserRole();
        $this->user_name = $this->username;
        $this->user_email = $this->email;
        $this->time_zone = 0;
        $this->language_id = 0;
        return $this;
    }

    public function getUserRole()
    {
        if (!$this->id || $this->isNewRecord) return null;

        $authManager = Yii::$app->authManager;
        if ($authManager->checkAccess($this->id, 'root')) {
            return 'root';
        }
        if ($authManager->checkAccess($this->id, 'admin')) {
            return 'admin';
        }
        if ($authManager->checkAccess($this->id, 'organizer')) {
            return 'organizer';
        }

        return null;
    }

    /**
     * Save tournament.
     *
     * @return bool whether the saving new account was successful
     * @throws \Exception
     */
    public function saveUser()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->username = $this->user_name;
        $this->email = $this->user_email;

        if ($this->isNewRecord) {
            $this->status = User::STATUS_ACTIVE;
            $this->setPassword($this->user_password);
            $this->generateAuthKey();
            //$this->generateEmailVerificationToken();
        }

        if (!$this->save()) {
            return false;
        }

        if ($this->id && in_array($this->user_role, ['root', 'admin', 'organizer'])) {
            if ($this->getUserRole() != $this->user_role) {
                $authManager = Yii::$app->authManager;

                $authManager->revokeAll($this->id);

                $authRole = $authManager->getRole($this->user_role);
                $authManager->assign($authRole, $this->id);
            }
        }

        return true;
    }

}