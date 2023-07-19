<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{

    /**
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $organizer = $auth->createRole('organizer');
        $auth->add($organizer);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $root = $auth->createRole('root');
        $auth->add($root);

        $createTournament = $auth->createPermission('createTournament');
        $createTournament->description = 'Create tournament';
        $auth->add($createTournament);

        $updateTournament = $auth->createPermission('updateTournament');
        $updateTournament->description = 'Update tournament';
        $auth->add($updateTournament);

        $deleteTournament = $auth->createPermission('deleteTournament');
        $deleteTournament->description = 'Delete tournament';
        $auth->add($deleteTournament);

        $tournamentOwnRule = new \common\rbac\TournamentOwnerRule();
        $auth->add($tournamentOwnRule);

        $updateOwnTournament = $auth->createPermission('updateOwnTournament');
        $updateOwnTournament->description = 'Update own tournament';
        $updateOwnTournament->ruleName = $tournamentOwnRule->name;
        $auth->add($updateOwnTournament);

        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create user';
        $auth->add($createUser);

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update user';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete user';
        $auth->add($deleteUser);

        $userOwnRule = new \common\rbac\UserOwnerRule();
        $auth->add($userOwnRule);

        $updateOwnUser = $auth->createPermission('updateOwnUser');
        $updateOwnUser->description = 'Update own user';
        $updateOwnUser->ruleName = $userOwnRule->name;
        $auth->add($updateOwnUser);



        $auth->addChild($updateOwnTournament, $updateTournament);
        $auth->addChild($updateOwnUser, $updateUser);



        $auth->addChild($organizer, $createTournament);
        $auth->addChild($organizer, $updateOwnTournament);
        $auth->addChild($organizer, $updateOwnUser);

        $auth->addChild($admin, $createTournament);
        $auth->addChild($admin, $updateTournament);
        $auth->addChild($admin, $deleteTournament);
        $auth->addChild($admin, $updateOwnUser);

        $auth->addChild($root, $createTournament);
        $auth->addChild($root, $updateTournament);
        $auth->addChild($root, $deleteTournament);
        $auth->addChild($root, $createUser);
        $auth->addChild($root, $updateUser);
        $auth->addChild($root, $deleteUser);



        $auth->assign($organizer, 2);
        $auth->assign($admin, 1);
        $auth->assign($root, 3);
    }
}