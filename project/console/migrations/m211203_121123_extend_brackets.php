<?php

use yii\db\Migration;

/**
 * Class m211203_121123_extend_brackets
 */
class m211203_121123_extend_brackets extends Migration
{
    public function safeUp()
    {
        // $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        // $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->addColumn('{{%bracket}}', 'editable_participants', $this->tinyInteger());
        $this->addColumn('{{%bracket}}', 'editable_scores', $this->tinyInteger());

        $this->execute('
            INSERT INTO `settings` (`id`, `title`, `description`, `key`, `value`) VALUES
            (8, "Главная - Интро (заголовок)", "", "home_intro_title", "Best world Tournaments of World of Warcraft"),
            (9, "Главная - Интро (описание)", "", "home_intro_desc", "Our project hosts tournaments and competitions in the game World of Warcraft: Shadowlands. Join us to become a champion and get prizes"),
            (10, "Главная - Интро (текст кнопки)", "", "home_intro_btn_text", "see all tournaments"),
            (11, "Главная - Интро (ссылка кнопки)", "", "home_intro_btn_link", "/tournaments");
        ');
    }

    public function safeDown()
    {
        echo "m211203_121123_extend_brackets cannot be reverted.\n";

        return false;
    }

}
