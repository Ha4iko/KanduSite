<?php

use yii\db\Migration;

/**
 * Class m211206_171502_twitch_domain
 */
class m211206_171502_twitch_domain extends Migration
{
    public function safeUp()
    {
        $this->execute('
            INSERT INTO `settings` (`id`, `title`, `description`, `key`, `value`) VALUES
            (12, "Домен для Twitch", NULL, "twitch_domain", "idl.com");
        ');
    }

    public function safeDown()
    {
        echo "m211206_171502_twitch_domain cannot be reverted.\n";

        return false;
    }

}
