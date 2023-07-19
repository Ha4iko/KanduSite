<?php

use yii\db\Migration;

/**
 * Class m211209_120103_fixes
 */
class m211209_120103_fixes extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tournament_type', 'players_in_team', $this->tinyInteger());
        $this->execute('
            UPDATE `tournament_type` SET `id` = 1,`name` = "1 vs 1",`description` = "solo",`team_mode` = 0,`slug` = "1-vs-1",`players_in_team` = NULL WHERE `tournament_type`.`id` = 1;
            UPDATE `tournament_type` SET `id` = 2,`name` = "1 vs 1 teams",`description` = "5 x 5 teams",`team_mode` = 1,`slug` = "1-vs-1-teams",`players_in_team` = 1 WHERE `tournament_type`.`id` = 2;
            UPDATE `tournament_type` SET `id` = 3,`name` = "2 vs 2",`description` = "teams",`team_mode` = 1,`slug` = "2-vs-2",`players_in_team` = 2 WHERE `tournament_type`.`id` = 3;
            UPDATE `tournament_type` SET `id` = 4,`name` = "3 vs 3",`description` = "teams",`team_mode` = 1,`slug` = "3-vs-3",`players_in_team` = 3 WHERE `tournament_type`.`id` = 4;
            UPDATE `tournament_type` SET `id` = 5,`name` = "5 vs 5",`description` = "teams",`team_mode` = 1,`slug` = "5-vs-5",`players_in_team` = 5 WHERE `tournament_type`.`id` = 5;
            UPDATE `tournament_type` SET `id` = 6,`name` = "BSG",`description` = "teams",`team_mode` = 1,`slug` = "bsg",`players_in_team` = NULL WHERE `tournament_type`.`id` = 6;
        ');
    }

    public function safeDown()
    {
        echo "m211209_120103_fixes cannot be reverted.\n";

        return false;
    }

}
