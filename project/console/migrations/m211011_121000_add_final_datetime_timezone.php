<?php

use yii\db\Migration;

/**
 * Class m211011_121000_add_final_datetime_timezone
 */
class m211011_121000_add_final_datetime_timezone extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tournament}}', 'time_zone', $this->integer());
        $this->addColumn('{{%tournament}}', 'date_final', $this->date()->after('date'));
        $this->addColumn('{{%tournament}}', 'time_final', $this->time()->after('time'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tournament}}', 'time_zone');
        $this->dropColumn('{{%tournament}}', 'date_final');
        $this->dropColumn('{{%tournament}}', 'time_final');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211011_121000_add_final_datetime_timezone cannot be reverted.\n";

        return false;
    }
    */
}
