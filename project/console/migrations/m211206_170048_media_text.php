<?php

use yii\db\Migration;

/**
 * Class m211206_170048_media_text
 */
class m211206_170048_media_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('media','content', 'LONGTEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211206_170048_media_text cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211206_170048_media_text cannot be reverted.\n";

        return false;
    }
    */
}
