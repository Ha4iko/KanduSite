<?php

use yii\db\Migration;

/**
 * Class m211216_075102_add_external_link
 */
class m211216_075102_add_external_link extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            delete from tournament_type where id > 6;
            alter table tournament_type AUTO_INCREMENT=7;
            update tournament_type set name = "BG", slug = "bg", players_in_team = 15 where id = 6;
            update tournament_type set name = "1 vs 1" where id = 2;
        ');

        $this->addColumn('player', 'external_link', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211216_075102_add_external_link cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211216_075102_last cannot be reverted.\n";

        return false;
    }
    */
}
