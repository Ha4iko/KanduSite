<?php

use yii\db\Migration;

/**
 * Class m211112_091022_add_bracket_swiss
 */
class m211112_091022_add_bracket_swiss extends Migration
{
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->addColumn('{{%bracket}}', 'round_count', $this->integer());
        $this->renameColumn('{{%bracket}}', 'groups', 'group_count');




        $this->createTable('{{%bracket_swiss_round}}', [
            'id' => $this->primaryKey()->unsigned(),
            'bracket_id' => $this->integer()->unsigned(),
            'order' => $this->integer(),
            'title' => $this->string(255),
            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-bracket_id",
            "{{%bracket_swiss_round}}",
            'bracket_id'
        );
        $this->addForeignKey('fk_swiss-round_bracket', '{{%bracket_swiss_round}}', 'bracket_id',
            "`$dbName`.`bracket`", 'id', 'CASCADE', 'RESTRICT');




        $this->createTable('{{%bracket_swiss_player_duel}}', [
            'id' => $this->primaryKey()->unsigned(),
            'round_id' => $this->integer()->unsigned(),
            'order' => $this->integer(),

            'player_one_id' => $this->integer()->unsigned(),
            'score_one' => $this->integer(),
            'scheme_one' => $this->tinyInteger(),
            'points_one' => $this->integer(),

            'player_two_id' => $this->integer()->unsigned(),
            'score_two' => $this->integer(),
            'scheme_two' => $this->tinyInteger(),
            'points_two' => $this->integer(),

            'winner_id' => $this->integer()->unsigned(),
            'loser_id' => $this->integer()->unsigned(),

            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-player_one_id",
            "{{%bracket_swiss_player_duel}}",
            'player_one_id'
        );
        $this->addForeignKey('fk_swiss-duel_player-one', '{{%bracket_swiss_player_duel}}', 'player_one_id',
            "`$dbName`.`tournament_to_player`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-player_two_id",
            "{{%bracket_swiss_player_duel}}",
            'player_two_id'
        );
        $this->addForeignKey('fk_swiss-duel_player-two', '{{%bracket_swiss_player_duel}}', 'player_two_id',
            "`$dbName`.`tournament_to_player`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-round_id",
            "{{%bracket_swiss_player_duel}}",
            'round_id'
        );
        $this->addForeignKey('fk_swiss-player-duel_round', '{{%bracket_swiss_player_duel}}', 'round_id',
            "`$dbName`.`bracket_swiss_round`", 'id', 'CASCADE', 'RESTRICT');






        $this->createTable('{{%bracket_swiss_team_duel}}', [
            'id' => $this->primaryKey()->unsigned(),
            'round_id' => $this->integer()->unsigned(),
            'order' => $this->integer(),

            'team_one_id' => $this->integer()->unsigned(),
            'score_one' => $this->integer(),
            'scheme_one' => $this->tinyInteger(),
            'points_one' => $this->integer(),

            'team_two_id' => $this->integer()->unsigned(),
            'score_two' => $this->integer(),
            'scheme_two' => $this->tinyInteger(),
            'points_two' => $this->integer(),

            'winner_id' => $this->integer()->unsigned(),
            'loser_id' => $this->integer()->unsigned(),

            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-team_one_id",
            "{{%bracket_swiss_team_duel}}",
            'team_one_id'
        );
        $this->addForeignKey('fk_swiss-duel_team-one', '{{%bracket_swiss_team_duel}}', 'team_one_id',
            "`$dbName`.`tournament_to_team`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-team_two_id",
            "{{%bracket_swiss_team_duel}}",
            'team_two_id'
        );
        $this->addForeignKey('fk_swiss-duel_team-two', '{{%bracket_swiss_team_duel}}', 'team_two_id',
            "`$dbName`.`tournament_to_team`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-round_id",
            "{{%bracket_swiss_team_duel}}",
            'round_id'
        );
        $this->addForeignKey('fk_swiss-team-duel_round', '{{%bracket_swiss_team_duel}}', 'round_id',
            "`$dbName`.`bracket_swiss_round`", 'id', 'CASCADE', 'RESTRICT');


    }

    public function safeDown()
    {
        echo "m211112_091022_add_bracket_swiss cannot be reverted.\n";

        return false;
    }

}
