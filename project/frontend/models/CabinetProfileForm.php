<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Cabinet profile form
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $avatar
 */
class CabinetProfileForm extends Model
{
    public $id;
    public $username;
    public $email;
    public $avatar;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['avatar', 'string', 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email', 'unique',
                'targetAttribute' => 'email',
                'targetClass' => '\common\models\User',
                'message' => 'This email address has already been taken.',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['not', ['id' => $this->id]]);
                    }
                }
            ],

            ['username', 'trim'],
            ['username', 'required'],
            [
                'username', 'unique',
                'targetAttribute' => 'username',
                'targetClass' => '\common\models\User',
                'message' => 'This nickname has already been taken.',
                'filter' => function ($query) {
                    if ($this->id) {
                        $query->andWhere(['not', ['id' => $this->id]]);
                    }
                }
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],
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
            'username' => 'Nickname',
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
        $this->username = $user->username;
        $this->email = $user->email;
        $this->avatar = file_exists(Yii::getAlias('@frontend/web' . $user->avatar))
            ? $user->avatar : IMG_ROOT . '/logo-big.png';

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
            $this->addError('email', 'User not found.');
            return false;
        }

        $user->username = $this->username;
        $user->email = $this->email;
        $this->upload($this, $user, 'avatar', 'user');
        $saved = $user->save();

        return $saved;
    }


    /**
     * @param Model $modelFrom
     * @param ActiveRecord $modelTo
     * @param string $attribute
     * @param string $entity
     * @return bool
     */
    private function upload($modelFrom, $modelTo, $attribute, $entity) {
        $image = UploadedFile::getInstance($modelFrom, $attribute);
        if ($image instanceof UploadedFile) {
            $filename = uniqid('img_') . '.' . $image->extension;
            $path = '/storage/images/' . $entity . '/';
            $dir = Yii::getAlias('@frontend/web') . $path;
            !file_exists($dir) && mkdir($dir, 0777, true);
            $image && $image->saveAs($dir . $filename) && $modelTo->$attribute = $path . $filename;
        }

        return true;
    }
}
