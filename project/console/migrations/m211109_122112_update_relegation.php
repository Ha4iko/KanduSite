<?php

use yii\db\Migration;

/**
 * Class m211109_122112_update_relegation
 */
class m211109_122112_update_relegation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->renameColumn('{{%bracket}}', 'rounds', 'best_of');
        $this->dropColumn('{{%bracket_relegation_player_duel}}', 'best_of');
        $this->dropColumn('{{%bracket_relegation_player_duel}}', 'loss_count_one');
        $this->dropColumn('{{%bracket_relegation_player_duel}}', 'loss_count_two');
        $this->dropColumn('{{%bracket_relegation_player_duel}}', 'loser_from_main');
        $this->dropColumn('{{%bracket_relegation_player_duel}}', 'type_id');
        $this->dropColumn('{{%bracket_relegation_team_duel}}', 'best_of');
        $this->dropColumn('{{%bracket_relegation_team_duel}}', 'loss_count_one');
        $this->dropColumn('{{%bracket_relegation_team_duel}}', 'loss_count_two');
        $this->dropColumn('{{%bracket_relegation_team_duel}}', 'loser_from_main');
        $this->dropColumn('{{%bracket_relegation_team_duel}}', 'type_id');

        $this->delete('bracket', 'bracket_type = 1');
        $this->dropForeignKey('fk_duel_player-one', 'bracket_relegation_player_duel');
        $this->dropForeignKey('fk_duel_player-two', 'bracket_relegation_player_duel');
        $this->dropForeignKey('fk_duel_team-one', 'bracket_relegation_team_duel');
        $this->dropForeignKey('fk_duel_team-two', 'bracket_relegation_team_duel');

        $this->addForeignKey('fk_duel_player-one', '{{%bracket_relegation_player_duel}}', 'player_one_id',
            "`$dbName`.`tournament_to_player`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_duel_player-two', '{{%bracket_relegation_player_duel}}', 'player_two_id',
            "`$dbName`.`tournament_to_player`", 'id', 'SET NULL', 'RESTRICT');

        $this->addForeignKey('fk_duel_team-one', '{{%bracket_relegation_team_duel}}', 'team_one_id',
            "`$dbName`.`tournament_to_team`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_duel_team-two', '{{%bracket_relegation_team_duel}}', 'team_two_id',
            "`$dbName`.`tournament_to_team`", 'id', 'SET NULL', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211109_122112_update_relegation cannot be reverted.\n";

        return false;
    }

}
