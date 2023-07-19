<?php

use yii\db\Migration;

/**
 * Class m211102_090124_add_brackets
 */
class m211102_090124_add_brackets extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->createTable('{{%bracket}}', [
            'id' => $this->primaryKey()->unsigned(),
            'tournament_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'bracket_type' => $this->integer(),
            'title' => $this->string(255),
            'order' => $this->integer(),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            "idx-tournament_id",
            "{{%bracket}}",
            'tournament_id'
        );
        $this->addForeignKey('fk_bracket_tournament', '{{%bracket}}', 'tournament_id',
            "`$dbName`.`tournament`", 'id', 'CASCADE', 'RESTRICT');


        $this->createTable('{{%bracket_table_column}}', [
            'id' => $this->primaryKey()->unsigned(),
            'bracket_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'column_type' => $this->integer(),
            'title' => $this->string(255),
            'active' => $this->integer(),
            'order' => $this->integer(),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            "idx-bracket_id",
            "{{%bracket_table_column}}",
            'bracket_id'
        );
        $this->addForeignKey('fk_bracket-column_bracket', '{{%bracket_table_column}}', 'bracket_id',
            "`$dbName`.`bracket`", 'id', 'CASCADE', 'RESTRICT');



        $this->createTable('{{%bracket_table_row}}', [
            'id' => $this->primaryKey()->unsigned(),
            'bracket_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'tournament_to_player_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            "idx-bracket_id",
            "{{%bracket_table_row}}",
            'bracket_id'
        );
        $this->addForeignKey('fk_bracket-row_bracket', '{{%bracket_table_row}}', 'bracket_id',
            "`$dbName`.`bracket`", 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex(
            "idx-tournament_to_player_id",
            "{{%bracket_table_row}}",
            'tournament_to_player_id'
        );
        $this->addForeignKey('fk_bracket-row_ttp', '{{%bracket_table_row}}', 'tournament_to_player_id',
            "`$dbName`.`tournament_to_player`", 'id', 'CASCADE', 'RESTRICT');





        $this->createTable('{{%bracket_table_row_team}}', [
            'id' => $this->primaryKey()->unsigned(),
            'bracket_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'tournament_to_team_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            "idx-bracket_id",
            "{{%bracket_table_row_team}}",
            'bracket_id'
        );
        $this->addForeignKey('fk_bracket-row-team_bracket', '{{%bracket_table_row_team}}', 'bracket_id',
            "`$dbName`.`bracket`", 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex(
            "idx-tournament_to_team_id",
            "{{%bracket_table_row_team}}",
            'tournament_to_team_id'
        );
        $this->addForeignKey('fk_bracket-row-team_ttt', '{{%bracket_table_row_team}}', 'tournament_to_team_id',
            "`$dbName`.`tournament_to_team`", 'id', 'CASCADE', 'RESTRICT');




        $this->createTable('{{%bracket_table_cell}}', [
            'id' => $this->primaryKey()->unsigned(),
            'bracket_table_column_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'bracket_table_row_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'value' => $this->string(255),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            "idx-bracket_table_column_id",
            "{{%bracket_table_cell}}",
            'bracket_table_column_id'
        );
        $this->addForeignKey('fk_bracket-cell_column', '{{%bracket_table_cell}}', 'bracket_table_column_id',
            "`$dbName`.`bracket_table_column`", 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex(
            "idx-bracket_table_row_id",
            "{{%bracket_table_cell}}",
            'bracket_table_row_id'
        );
        $this->addForeignKey('fk_bracket-cell_row', '{{%bracket_table_cell}}', 'bracket_table_row_id',
            "`$dbName`.`bracket_table_row`", 'id', 'CASCADE', 'RESTRICT');





        $this->createTable('{{%bracket_table_cell_team}}', [
            'id' => $this->primaryKey()->unsigned(),
            'bracket_table_column_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'bracket_table_row_id' => $this->integer(10)->unsigned()->defaultValue(null),
            'value' => $this->string(255),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            "idx-bracket_table_column_id",
            "{{%bracket_table_cell_team}}",
            'bracket_table_column_id'
        );
        $this->addForeignKey('fk_bracket-cell-team_column', '{{%bracket_table_cell_team}}', 'bracket_table_column_id',
            "`$dbName`.`bracket_table_column`", 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex(
            "idx-bracket_table_row_id",
            "{{%bracket_table_cell_team}}",
            'bracket_table_row_id'
        );
        $this->addForeignKey('fk_bracket-cell-team_row', '{{%bracket_table_cell_team}}', 'bracket_table_row_id',
            "`$dbName`.`bracket_table_row_team`", 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211102_090124_add_brackets cannot be reverted.\n";

        return false;
    }

}
