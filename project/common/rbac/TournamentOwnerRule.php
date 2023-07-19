<?php

namespace common\rbac;

use common\models\Tournament;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Проверяем владельца турнира на соответствие с пользователем, переданным через параметры
 */
class TournamentOwnerRule extends Rule
{
    public $name = 'isTournamentOwner';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['tournamentId']) && !is_numeric($params['tournamentId'])) {
            throw new \Exception('Tournament id must be numeric');
        }
        $model = isset($params['tournamentId']) ? Tournament::findOne($params['tournamentId']) : null;
        return $model ? $model->organizer_id === $user : false;
    }
}
