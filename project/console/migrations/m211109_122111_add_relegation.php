<?php

use yii\db\Migration;

/**
 * Class m211109_122111_add_relegation
 */
class m211109_122111_add_relegation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();



        $this->addColumn('{{%bracket}}', 'participants', $this->integer());
        $this->addColumn('{{%bracket}}', 'rounds', $this->integer());
        $this->addColumn('{{%bracket}}', 'third_place', $this->tinyInteger());
        $this->addColumn('{{%bracket}}', 'second_defeat', $this->tinyInteger());




        $this->createTable('{{%bracket_relegation_round}}', [
            'id' => $this->primaryKey()->unsigned(),
            'bracket_id' => $this->integer()->unsigned(),
            'level' => $this->integer(),
            'type_id' => $this->tinyInteger(),
            'loser_from_main' => $this->tinyInteger(),
            'title' => $this->string(255),
            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-bracket_id",
            "{{%bracket_relegation_round}}",
            'bracket_id'
        );
        $this->addForeignKey('fk_round_bracket', '{{%bracket_relegation_round}}', 'bracket_id',
            "`$dbName`.`bracket`", 'id', 'CASCADE', 'RESTRICT');




        $this->createTable('{{%bracket_relegation_player_duel}}', [
            'id' => $this->primaryKey()->unsigned(),
            'round_id' => $this->integer()->unsigned(),
            'level' => $this->integer(),
            'order' => $this->integer(),
            'type_id' => $this->tinyInteger(),
            'loser_from_main' => $this->tinyInteger(),
            'best_of' => $this->tinyInteger(),

            'player_one_id' => $this->integer()->unsigned(),
            'score_one' => $this->integer(),
            'loss_count_one' => $this->integer(),

            'player_two_id' => $this->integer()->unsigned(),
            'score_two' => $this->integer(),
            'loss_count_two' => $this->integer(),

            'winner_id' => $this->integer(),
            'winner_to_duel_id' => $this->integer()->unsigned(),
            'loser_id' => $this->integer(),
            'loser_to_duel_id' => $this->integer()->unsigned(),

            'completed' => $this->tinyInteger(),
            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-player_one_id",
            "{{%bracket_relegation_player_duel}}",
            'player_one_id'
        );
        $this->addForeignKey('fk_duel_player-one', '{{%bracket_relegation_player_duel}}', 'player_one_id',
            "`$dbName`.`player`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-player_two_id",
            "{{%bracket_relegation_player_duel}}",
            'player_two_id'
        );
        $this->addForeignKey('fk_duel_player-two', '{{%bracket_relegation_player_duel}}', 'player_two_id',
            "`$dbName`.`player`", 'id', 'SET NULL', 'RESTRICT');

        $this->addForeignKey('fk_duel_winner-duel', '{{%bracket_relegation_player_duel}}', 'winner_to_duel_id',
            "`$dbName`.`bracket_relegation_player_duel`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_duel_loser-duel', '{{%bracket_relegation_player_duel}}', 'loser_to_duel_id',
            "`$dbName`.`bracket_relegation_player_duel`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-round_id",
            "{{%bracket_relegation_player_duel}}",
            'round_id'
        );
        $this->addForeignKey('fk_duel_round', '{{%bracket_relegation_player_duel}}', 'round_id',
            "`$dbName`.`bracket_relegation_round`", 'id', 'CASCADE', 'RESTRICT');




        $this->createTable('{{%bracket_relegation_team_duel}}', [
            'id' => $this->primaryKey()->unsigned(),
            'round_id' => $this->integer()->unsigned(),
            'level' => $this->integer(),
            'order' => $this->integer(),
            'type_id' => $this->tinyInteger(),
            'loser_from_main' => $this->tinyInteger(),
            'best_of' => $this->tinyInteger(),

            'team_one_id' => $this->integer()->unsigned(),
            'score_one' => $this->integer(),
            'loss_count_one' => $this->integer(),

            'team_two_id' => $this->integer()->unsigned(),
            'score_two' => $this->integer(),
            'loss_count_two' => $this->integer(),

            'winner_id' => $this->integer(),
            'winner_to_duel_id' => $this->integer()->unsigned(),
            'loser_id' => $this->integer(),
            'loser_to_duel_id' => $this->integer()->unsigned(),

            'completed' => $this->tinyInteger(),
            'active' => $this->tinyInteger(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            "idx-team_one_id",
            "{{%bracket_relegation_team_duel}}",
            'team_one_id'
        );
        $this->addForeignKey('fk_duel_team-one', '{{%bracket_relegation_team_duel}}', 'team_one_id',
            "`$dbName`.`team`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-team_two_id",
            "{{%bracket_relegation_team_duel}}",
            'team_two_id'
        );
        $this->addForeignKey('fk_duel_team-two', '{{%bracket_relegation_team_duel}}', 'team_two_id',
            "`$dbName`.`team`", 'id', 'SET NULL', 'RESTRICT');

        $this->addForeignKey('fk_team-duel_winner-duel', '{{%bracket_relegation_team_duel}}', 'winner_to_duel_id',
            "`$dbName`.`bracket_relegation_team_duel`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_team-duel_loser-duel', '{{%bracket_relegation_team_duel}}', 'loser_to_duel_id',
            "`$dbName`.`bracket_relegation_team_duel`", 'id', 'SET NULL', 'RESTRICT');

        $this->createIndex(
            "idx-round_id",
            "{{%bracket_relegation_team_duel}}",
            'round_id'
        );
        $this->addForeignKey('fk_team-duel_round', '{{%bracket_relegation_team_duel}}', 'round_id',
            "`$dbName`.`bracket_relegation_round`", 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211109_122111_add_relegation cannot be reverted.\n";

        return false;
    }

}
