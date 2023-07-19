<?php

use yii\db\Migration;

/**
 * Class m211112_091022_add_bracket_group
 */
class m211112_091022_add_bracket_group extends Migration
{
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->addColumn('{{%bracket}}', 'groups', $this->integer());




        $this->createTable('{{%bracket_group_round}}', [
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
            "{{%bracket_group_round}}",
            'bracket_id'
        );
        $this->addForeignKey('fk_group-round_bracket', '{{%bracket_group_round}}', 'bracket_id',
            "`$dbName`.`bracket`", 'id', 'CASCADE', 'RESTRICT');





        $this->createTable('{{%bracket_group_group}}', [
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
            "{{%bracket_group_group}}",
            'bracket_id'
        );
        $this->addForeignKey('fk_group-group_bracket', '{{%bracket_group_group}}', 'bracket_id',
            "`$dbName`.`bracket`", 'id', 'CASCADE', 'RESTRICT');




        $this->createTable('{{%bracket_group_player_duel}}', [
            'id' => $this->primaryKey()->unsigned(),
            'round_id' => $this->integer()->unsigned(),
            'group_id' => $this->integer()->unsigned(),
            'order' => $this->integer(),

            'player_one_id' => $this->integer()->unsigned(),
            'score_one' => $this->integer(),
            'scheme_one' => $this->tinyInteger(),

            'player_two_id' => $this->integer()->unsigned(),
            'score_two' => $this->integer(),
            'scheme_two' => $this->tinyInteger(),

            'winner_id' => $this->integer()->unsigned(),
            'loser_id' => $this->integer()->unsigned(),

            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-player_one_id",
            "{{%bracket_group_player_duel}}",
            'player_one_id'
        );
        $this->addForeignKey('fk_group-duel_player-one', '{{%bracket_group_player_duel}}', 'player_one_id',
            "`$dbName`.`tournament_to_player`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-player_two_id",
            "{{%bracket_group_player_duel}}",
            'player_two_id'
        );
        $this->addForeignKey('fk_group-duel_player-two', '{{%bracket_group_player_duel}}', 'player_two_id',
            "`$dbName`.`tournament_to_player`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-round_id",
            "{{%bracket_group_player_duel}}",
            'round_id'
        );
        $this->addForeignKey('fk_group-player-duel_round', '{{%bracket_group_player_duel}}', 'round_id',
            "`$dbName`.`bracket_group_round`", 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex(
            "idx-group_id",
            "{{%bracket_group_player_duel}}",
            'group_id'
        );
        $this->addForeignKey('fk_group-player-duel_group', '{{%bracket_group_player_duel}}', 'group_id',
            "`$dbName`.`bracket_group_group`", 'id', 'CASCADE', 'RESTRICT');






        $this->createTable('{{%bracket_group_team_duel}}', [
            'id' => $this->primaryKey()->unsigned(),
            'round_id' => $this->integer()->unsigned(),
            'group_id' => $this->integer()->unsigned(),
            'order' => $this->integer(),

            'team_one_id' => $this->integer()->unsigned(),
            'score_one' => $this->integer(),
            'scheme_one' => $this->tinyInteger(),

            'team_two_id' => $this->integer()->unsigned(),
            'score_two' => $this->integer(),
            'scheme_two' => $this->tinyInteger(),

            'winner_id' => $this->integer()->unsigned(),
            'loser_id' => $this->integer()->unsigned(),

            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-team_one_id",
            "{{%bracket_group_team_duel}}",
            'team_one_id'
        );
        $this->addForeignKey('fk_group-duel_team-one', '{{%bracket_group_team_duel}}', 'team_one_id',
            "`$dbName`.`tournament_to_team`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-team_two_id",
            "{{%bracket_group_team_duel}}",
            'team_two_id'
        );
        $this->addForeignKey('fk_group-duel_team-two', '{{%bracket_group_team_duel}}', 'team_two_id',
            "`$dbName`.`tournament_to_team`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-round_id",
            "{{%bracket_group_team_duel}}",
            'round_id'
        );
        $this->addForeignKey('fk_group-team-duel_round', '{{%bracket_group_team_duel}}', 'round_id',
            "`$dbName`.`bracket_group_round`", 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex(
            "idx-group_id",
            "{{%bracket_group_team_duel}}",
            'group_id'
        );
        $this->addForeignKey('fk_group-team-duel_group', '{{%bracket_group_team_duel}}', 'group_id',
            "`$dbName`.`bracket_group_group`", 'id', 'CASCADE', 'RESTRICT');


    }

    public function safeDown()
    {
        echo "m211112_091022_add_bracket_group cannot be reverted.\n";

        return false;
    }

}
