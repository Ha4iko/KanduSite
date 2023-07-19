<?php

use yii\db\Migration;

/**
 * Class m211014_082654_add_fields_for_prizes
 */
class m211014_082654_add_fields_for_prizes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE `tournament_prize` CHANGE `money` `money` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;');
        $this->execute('ALTER TABLE `tournament` ADD `prize_one` VARCHAR(50) NULL AFTER `time_zone`, ADD `prize_two` VARCHAR(50) NULL AFTER `prize_one`, ADD `prize_three` VARCHAR(50) NULL AFTER `prize_two`, ADD `prize_four` VARCHAR(50) NULL AFTER `prize_three`;');
        $this->execute('ALTER TABLE `tournament_prize` CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211014_082654_add_fields_for_prizes cannot be reverted.\n";

        return false;
    }

}
