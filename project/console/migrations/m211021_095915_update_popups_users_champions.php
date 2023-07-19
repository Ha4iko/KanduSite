<?php

use yii\db\Migration;

/**
 * Class m211021_095915_update_popups_users_champions
 */
class m211021_095915_update_popups_users_champions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->addColumn('{{%tournament_to_team}}', 'reward_base',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_team}}', 'reward_dyna',
            $this->integer(10)->unsigned()->null()->defaultValue(null));

        $this->createTable('{{%tournament_media}}', [
            'id' => $this->primaryKey()->unsigned(),
            'tournament_id' => $this->integer()->unsigned()->null()->defaultValue(null),
            'content' => $this->text(),
        ], $tableOptions);

        $this->createIndex(
            "idx-tournament_id",
            "{{%tournament_media}}",
            'tournament_id'
        );

        $this->addForeignKey('fk_tournament-media_tournament', '{{%tournament_media}}', 'tournament_id',
            "`$dbName`.`tournament`", 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%tournament_schedule}}', [
            'id' => $this->primaryKey()->unsigned(),
            'tournament_id' => $this->integer()->unsigned()->null()->defaultValue(null),
            'title' => $this->string(255),
            'date' => $this->date()->null()->defaultValue(null),
            'time' => $this->time()->null()->defaultValue(null),
            'order' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex(
            "idx-tournament_id",
            "{{%tournament_schedule}}",
            'tournament_id'
        );

        $this->addForeignKey('fk_tournament-schedule_tournament', '{{%tournament_schedule}}', 'tournament_id',
            "`$dbName`.`tournament`", 'id', 'CASCADE', 'RESTRICT');

        $this->addColumn('{{%tournament}}', 'schedule_type',
            $this->tinyInteger()->unsigned()->notNull()->defaultValue(0));
        $this->addColumn('{{%tournament}}', 'show_on_main_page',
            $this->tinyInteger()->unsigned()->notNull()->defaultValue(0));
        $this->addColumn('{{%tournament}}', 'is_primary',
            $this->tinyInteger()->unsigned()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211021_095915_update_popups_users_champions cannot be reverted.\n";

        return false;
    }
}
