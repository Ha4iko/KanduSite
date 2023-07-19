<?php

use yii\db\Migration;

/**
 * Class m211014_082653_add_fields_in_tournaments
 */
class m211014_082653_add_fields_in_tournaments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tournament_rule}}', 'order', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211014_082653_add_fields_in_tournaments cannot be reverted.\n";

        return false;
    }

}
