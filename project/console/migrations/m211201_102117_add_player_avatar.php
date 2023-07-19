<?php

use yii\db\Migration;

/**
 * Class m211201_102117_add_player_avatar
 */
class m211201_102117_add_player_avatar extends Migration
{
    public function safeUp()
    {
        // $tableOptions = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";
        // $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->addColumn('{{%player}}', 'avatar', $this->string(255));
        $this->addColumn('{{%player_race}}', 'avatar', $this->string(255));

        $this->execute('
            UPDATE `player_race` SET `id` = 1,`name` = "Human",`avatar` = "/storage/images/race/img_61a7757bd44fe.png" WHERE `player_race`.`id` = 1;
            UPDATE `player_race` SET `id` = 2,`name` = "Dwarf",`avatar` = "/storage/images/race/img_61a77582e0688.png" WHERE `player_race`.`id` = 2;
            UPDATE `player_race` SET `id` = 3,`name` = "Night Elf",`avatar` = "/storage/images/race/img_61a7758e730e8.png" WHERE `player_race`.`id` = 3;
            UPDATE `player_race` SET `id` = 4,`name` = "Gnome",`avatar` = "/storage/images/race/img_61a77596cda9a.png" WHERE `player_race`.`id` = 4;
            UPDATE `player_race` SET `id` = 5,`name` = "Draenei",`avatar` = "/storage/images/race/img_61a7759f67395.png" WHERE `player_race`.`id` = 5;
            UPDATE `player_race` SET `id` = 6,`name` = "Worgen",`avatar` = "/storage/images/race/img_61a775a8716f4.png" WHERE `player_race`.`id` = 6;
            UPDATE `player_race` SET `id` = 7,`name` = "Pandaren",`avatar` = "/storage/images/race/img_61a775b4d640b.png" WHERE `player_race`.`id` = 7;
            UPDATE `player_race` SET `id` = 8,`name` = "Orc",`avatar` = "/storage/images/race/img_61a775bd1ed34.png" WHERE `player_race`.`id` = 8;
            UPDATE `player_race` SET `id` = 9,`name` = "Undead",`avatar` = "/storage/images/race/img_61a775c5c557d.png" WHERE `player_race`.`id` = 9;
            UPDATE `player_race` SET `id` = 10,`name` = "Tauren",`avatar` = "/storage/images/race/img_61a775cf889db.png" WHERE `player_race`.`id` = 10;
            UPDATE `player_race` SET `id` = 11,`name` = "Troll",`avatar` = "/storage/images/race/img_61a775d8afc60.png" WHERE `player_race`.`id` = 11;
            UPDATE `player_race` SET `id` = 12,`name` = "Blood Elf",`avatar` = "/storage/images/race/img_61a775df5bc33.png" WHERE `player_race`.`id` = 12;
            UPDATE `player_race` SET `id` = 13,`name` = "Goblin",`avatar` = "/storage/images/race/img_61a775e6d2c98.png" WHERE `player_race`.`id` = 13;
            UPDATE `player_race` SET `id` = 14,`name` = "Void Elf",`avatar` = "/storage/images/race/img_61a775eeb3e6a.png" WHERE `player_race`.`id` = 14;
            UPDATE `player_race` SET `id` = 15,`name` = "Lightforged Draenei",`avatar` = "/storage/images/race/img_61a775f7b7718.png" WHERE `player_race`.`id` = 15;
            UPDATE `player_race` SET `id` = 16,`name` = "Dark Iron Dwarf",`avatar` = "/storage/images/race/img_61a775ff88e9a.png" WHERE `player_race`.`id` = 16;
            UPDATE `player_race` SET `id` = 17,`name` = "Kul Tiran",`avatar` = "/storage/images/race/img_61a776075eb69.png" WHERE `player_race`.`id` = 17;
            UPDATE `player_race` SET `id` = 18,`name` = "Mechagnome",`avatar` = "/storage/images/race/img_61a77610b059a.png" WHERE `player_race`.`id` = 18;
            UPDATE `player_race` SET `id` = 19,`name` = "Nightborne",`avatar` = "/storage/images/race/img_61a77618bfeb1.png" WHERE `player_race`.`id` = 19;
            UPDATE `player_race` SET `id` = 20,`name` = "Highmountain Tauren",`avatar` = "/storage/images/race/img_61a77621cdb46.png" WHERE `player_race`.`id` = 20;
            UPDATE `player_race` SET `id` = 21,`name` = "Maghar Orc",`avatar` = "/storage/images/race/img_61a7764ef3967.png" WHERE `player_race`.`id` = 21;
            UPDATE `player_race` SET `id` = 22,`name` = "Zandalari Troll",`avatar` = "/storage/images/race/img_61a7765c416ab.png" WHERE `player_race`.`id` = 22;
            UPDATE `player_race` SET `id` = 23,`name` = "Vulpera",`avatar` = "/storage/images/race/img_61a7767a2c919.png" WHERE `player_race`.`id` = 23;
        ');
    }

    public function safeDown()
    {
        echo "m211201_102117_add_player_avatar cannot be reverted.\n";

        return false;
    }

}
