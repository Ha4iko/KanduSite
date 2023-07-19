<?php

use yii\db\Migration;

/**
 * Class m211214_130304_add_second_prize
 */
class m211214_130304_add_second_prize extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%tournament_to_player}}', 'reward_dyna_sec',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
        $this->addColumn('{{%tournament_to_team}}', 'reward_dyna_sec',
            $this->integer(10)->unsigned()->null()->defaultValue(null));
    }

    public function safeDown()
    {
        echo "m211214_130304_add_second_prize cannot be reverted.\n";

        return false;
    }

}
