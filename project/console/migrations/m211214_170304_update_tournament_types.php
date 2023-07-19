<?php

use yii\db\Migration;

/**
 * Class m211214_170304_update_tournament_types
 */
class m211214_170304_update_tournament_types extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%tournament_type}}', 'bsg',
            $this->tinyInteger());

        $this->execute('
            UPDATE `tournament_type` SET `id` = 1,`name` = "1 vs 1",`description` = "solo",`team_mode` = 0,`slug` = "1vs1",`players_in_team` = 1,`bsg` = 0 WHERE `tournament_type`.`id` = 1;
            UPDATE `tournament_type` SET `id` = 2,`name` = "1 vs 1 teams",`description` = "5 x 5 teams",`team_mode` = 1,`slug` = "1vs1-5x5",`players_in_team` = 5,`bsg` = 0 WHERE `tournament_type`.`id` = 2;
            UPDATE `tournament_type` SET `id` = 3,`name` = "2 vs 2",`description` = "teams",`team_mode` = 1,`slug` = "2vs2",`players_in_team` = 2,`bsg` = 0 WHERE `tournament_type`.`id` = 3;
            UPDATE `tournament_type` SET `id` = 4,`name` = "3 vs 3",`description` = "teams",`team_mode` = 1,`slug` = "3vs3",`players_in_team` = 3,`bsg` = 0 WHERE `tournament_type`.`id` = 4;
            UPDATE `tournament_type` SET `id` = 5,`name` = "5 vs 5",`description` = "teams",`team_mode` = 1,`slug` = "5vs5",`players_in_team` = 5,`bsg` = 0 WHERE `tournament_type`.`id` = 5;
            UPDATE `tournament_type` SET `id` = 6,`name` = "BSG 6",`description` = "teams",`team_mode` = 1,`slug` = "bsg6",`players_in_team` = 6,`bsg` = 1 WHERE `tournament_type`.`id` = 6;
        ');

        $this->execute('
            INSERT INTO `tournament_type` (`id`, `name`, `description`, `team_mode`, `slug`, `players_in_team`, `bsg`) VALUES
            (7, "BSG 7", "teams", 1, "bsg7", 7, 1),
            (8, "BSG 8", "teams", 1, "bsg8", 8, 1),
            (9, "BSG 9", "teams", 1, "bsg9", 9, 1),
            (10, "BSG 10", "teams", 1, "bsg10", 10, 1);
        ');
    }

    public function safeDown()
    {
        echo "m211214_170304_update_tournament_types cannot be reverted.\n";

        return false;
    }

}
