<?php

use yii\db\Migration;

/**
 * Class m211123_123247_brackets_extend
 */
class m211123_123247_brackets_extend extends Migration
{
    public function safeUp()
    {
        // $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        // $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->addColumn('{{%bracket}}', 'editable', $this->tinyInteger());
        $this->execute('update `bracket` set editable = 1;');
    }

    public function safeDown()
    {
        echo "m211123_123247_brackets_extend cannot be reverted.\n";

        return false;
    }

}
