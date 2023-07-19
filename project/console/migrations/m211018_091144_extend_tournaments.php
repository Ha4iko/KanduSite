<?php

use yii\db\Migration;

/**
 * Class m211018_091144_extend_tournaments
 */
class m211018_091144_extend_tournaments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->addColumn('{{%tournament_to_player}}', 'class_id',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_player}}', 'race_id',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_player}}', 'faction_id',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_player}}', 'world_id',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_player}}', 'team_id',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_player}}', 'reward_base',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_player}}', 'reward_dyna',
            $this->integer(10)->unsigned()->null()->defaultValue(null));

        $this->addForeignKey('fk_tour-to-player_class', '{{%tournament_to_player}}', 'class_id',
            "`$dbName`.`player_class`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tour-to-player_faction', '{{%tournament_to_player}}', 'faction_id',
            "`$dbName`.`player_faction`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tour-to-player_race', '{{%tournament_to_player}}', 'race_id',
            "`$dbName`.`player_race`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tour-to-player_world', '{{%tournament_to_player}}', 'world_id',
            "`$dbName`.`player_world`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tour-to-player_team', '{{%tournament_to_player}}', 'team_id',
            "`$dbName`.`team`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tour-to-player_reward', '{{%tournament_to_player}}', 'reward_dyna',
            "`$dbName`.`tournament_prize`", 'id', 'SET NULL', 'RESTRICT');

        $this->addColumn('{{%tournament_type}}', 'description',
            $this->string(255)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211018_091144_extend_tournaments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211018_091144_add_player_params_to_participants cannot be reverted.\n";

        return false;
    }
    */
}
