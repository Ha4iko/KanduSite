<?php

namespace common\rbac;

use common\models\User;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Проверяем владельца учетки личного кабинета на соответствие с пользователем, переданным через параметры
 */
class UserOwnerRule extends Rule
{
    public $name = 'isUserOwner';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $model = isset($params['userId']) ? User::findOne($params['userId']) : null;
        return $model ? $model->id === $user : false;
    }
}
