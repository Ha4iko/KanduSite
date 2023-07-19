<?php

use yii\db\Migration;

/**
 * Class m211102_151324_preset_data
 */
class m211102_151324_preset_data extends Migration
{


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%language}}', ['id' => '1', 'name' => 'English']);
        $this->insert('{{%language}}', ['id' => '2', 'name' => 'Russian']);

        $this->execute('
            INSERT INTO `settings` (`id`, `title`, `description`, `key`, `value`) VALUES
            (1, "Ники для страницы Thanks", "", "thanks_nicks", "Gerald Fox\r\nJason Carpenter\r\nLeonard Hughes\r\nGerald Fox\r\nJason Carpenter\r\nLeonard Hughes\r\nGerald Fox\r\nJason Carpenter\r\nLeonard Hughes\r\nGerald Fox\r\nJason Carpenter\r\nLeonard Hughes\r\nGerald Fox\r\nJason Carpenter\r\nLeonard Hughes\r\nGerald Fox\r\nJason Carpenter\r\nLeonard Hughes\r\nHuman\r\n"),
            (2, "Донаты для страницы Donate", "", "donate_content", \'<div style=\"background-color: #7B7B7B; padding-bottom: 51%;\"></div>\'),
            (3, "Телеграм для страницы Contacts", "", "contacts_telegram", "idl"),
            (4, "Почта для страницы Contacts", "", "contacts_email", "hi@idl.com"),
            (5, "Главная - Видео (заголовок)", "", "home_trailer_title", "watch trailer<br>\r\nabout idl project"),
            (6, "Главная - Видео (ссылка на youtube)", "", "home_trailer_link", "https://www.youtube.com/watch?v=iQVei5C2N4E"),
            (7, "Главная - Видео (описание)", "", "home_trailer_desc", "Some text about trailer. Our project hosts tournaments and competitions in the game World of Warcraft: Shadowlands. Join us to become a champion and get prizes");
        ');
        $this->execute('
            INSERT INTO `player_class` (`id`, `name`, `avatar`) VALUES
            (1, "Warrior", "champ-avatar2.jpg"),
            (2, "Paladin", "champ-avatar3.jpg"),
            (3, "Hunder", "champ-avatar3.jpg"),
            (4, "Rogue", "champ-avatar1.jpg"),
            (5, "Priest", "champ-avatar1.jpg"),
            (6, "Shaman", "champ-avatar1.jpg"),
            (7, "Mage", "champ-avatar1.jpg"),
            (8, "Warlock", "champ-avatar1.jpg"),
            (9, "Monk", "champ-avatar1.jpg"),
            (10, "Druid", "champ-avatar1.jpg"),
            (11, "Demon Hunter", "champ-avatar1.jpg"),
            (12, "Death Knight", "champ-avatar1.jpg");
        ');
        $this->execute('
            INSERT INTO `player_faction` (`id`, `name`, `avatar`) VALUES
            (1, "Alliance", "custom-logo1.png"),
            (2, "Horde", "custom-logo2.png");
        ');
        $this->execute('
            INSERT INTO `player_race` (`id`, `name`) VALUES
            (1, "Human"),
            (2, "Dwarf"),
            (3, "Night Elf"),
            (4, "Gnome"),
            (5, "Draenei"),
            (6, "Worgen"),
            (7, "Pandaren"),
            (8, "Orc"),
            (9, "Undead"),
            (10, "Tauren"),
            (11, "Troll"),
            (12, "Blood Elf"),
            (13, "Goblin"),
            (14, "Void Elf"),
            (15, "Lightforged Draenei"),
            (16, "Dark Iron Dwarf"),
            (17, "Kul Tiran"),
            (18, "Mechagnome"),
            (19, "Nightborne"),
            (20, "Highmountain Tauren"),
            (21, "Maghar Orc"),
            (22, "Zandalari Troll"),
            (23, "Vulpera");
        ');
        $this->execute('
            INSERT INTO `player_world` (`id`, `name`) VALUES
            (1, "Azeroth"),
            (2, "Draenor"),
            (3, "Outland");
        ');
        $this->execute('
            INSERT INTO `tournament_type` (`id`, `name`, `description`, `team_mode`, `slug`) VALUES
            (1, "1 VS 1", "solo", 0, "1-vs-1"),
            (2, "1 VS 1 teams", "5 x 5 teams", 1, "1-vs-1-teams"),
            (3, "2 vs 2", "teams", 1, "2-vs-2"),
            (4, "3 vs 3", "teams", 1, "3-vs-3"),
            (5, "5 vs 5", "teams", 1, "5-vs-5"),
            (6, "BSG", "teams", 1, "bsg");
        ');



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211102_151324_preset_data cannot be reverted.\n";

        return false;
    }

}
