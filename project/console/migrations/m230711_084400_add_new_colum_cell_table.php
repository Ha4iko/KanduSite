<?php

use yii\db\Migration;

/**
 * Class m230711_084400_add_new_colum_cell_table
 */
class m230711_084400_add_new_colum_cell_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bracket_table_cell}}', 'top', $this->integer(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230711_084400_add_new_colum_cell_table cannot be reverted.\n";

        return false;
    }

}
