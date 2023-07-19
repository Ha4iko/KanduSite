<?php

use yii\db\Migration;

/**
 * Class m230715_115213_add_new_colum_cell_team_table
 */
class m230715_115213_add_new_colum_cell_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bracket_table_cell_team}}', 'top', $this->integer(10));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230715_115213_add_new_colum_cell_team_table cannot be reverted.\n";

        return false;
    }
}
