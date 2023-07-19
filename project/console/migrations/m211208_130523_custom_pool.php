<?php

use yii\db\Migration;

/**
 * Class m211208_130523_custom_pool
 */
class m211208_130523_custom_pool extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tournament', 'pool_custom', $this->string(20));
    }

    public function safeDown()
    {
        echo "m211208_130523_custom_pool cannot be reverted.\n";

        return false;
    }

}
