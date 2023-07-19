<?php

use yii\db\Migration;

/**
 * Class m211028_095707_fixes
 */
class m211028_095707_fixes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->string(255),
            'key' => $this->string(255),
            'value' => $this->text(),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->execute('ALTER TABLE `tournament_to_player` DROP FOREIGN KEY `fk_tourn-player_player`; ALTER TABLE `tournament_to_player` ADD CONSTRAINT `fk_tourn-player_player` FOREIGN KEY (`player_id`) REFERENCES `player`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;');
        $this->execute('ALTER TABLE `tournament_to_team` DROP FOREIGN KEY `fk_tourn-team_team`; ALTER TABLE `tournament_to_team` ADD CONSTRAINT `fk_tourn-team_team` FOREIGN KEY (`team_id`) REFERENCES `team`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;');

        $this->addColumn('{{%tournament_type}}', 'team_mode',
            $this->tinyInteger());
        $this->addColumn('{{%player_class}}', 'avatar',
            $this->string(255));
        $this->addColumn('{{%player_faction}}', 'avatar',
            $this->string(255));
        $this->addColumn('{{%tournament_type}}', 'slug',
            $this->string(255));

        $this->createTable('{{%media}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255),
            'title' => $this->string(255),
            'date' => $this->date(),
            'content' => $this->text(),
            'is_text' => $this->tinyInteger(),
            'is_video' => $this->tinyInteger(),
            'active' => $this->tinyInteger(),
            'bg_image' => $this->string(255),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211028_095707_fixes cannot be reverted.\n";

        return false;
    }

}
