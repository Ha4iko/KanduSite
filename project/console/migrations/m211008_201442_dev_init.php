<?php

use yii\db\Migration;

class m211008_201442_dev_init extends Migration
{
    public function up()
    {
        $tableOptions_mysql = "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB";

        $dbName = Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();

        $this->createTable('{{%language}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'name' => 'VARCHAR(20) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%page}}', [
            'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'slug' => 'VARCHAR(255) NOT NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'body' => 'TEXT NOT NULL',
            'active' => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'meta_title' => 'VARCHAR(255) NOT NULL',
            'meta_description' => 'VARCHAR(1024) NOT NULL',
            'og_title' => 'VARCHAR(255) NOT NULL',
            'og_description' => 'VARCHAR(1024) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%player}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'nick' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%player_class}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'name' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%player_faction}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'name' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%player_race}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'name' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%player_world}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'name' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%team}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'name' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%team_to_player}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'team_id' => 'INT(10) UNSIGNED NOT NULL',
            'player_id' => 'INT(10) UNSIGNED NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%tournament}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'title' => 'VARCHAR(255) NOT NULL',
            'slug' => 'VARCHAR(255) NOT NULL',
            'status' => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\'',
            'pool' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\'',
            'date' => 'DATE NULL',
            'time' => 'TIME NULL',
            'type_id' => 'INT(10) UNSIGNED NULL',
            'bg_image' => 'VARCHAR(255) NULL',
            'organizer_id' => 'INT(10) UNSIGNED NULL',
            'language_id' => 'INT(10) UNSIGNED NULL',
            'created_by' => 'INT(10) UNSIGNED NULL',
            'updated_by' => 'INT(10) UNSIGNED NULL',
            'created_at' => 'DATETIME NULL',
            'updated_at' => 'DATETIME NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%tournament_prize}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'tournament_id' => 'INT(10) UNSIGNED NOT NULL',
            'type_id' => 'TINYINT(3) UNSIGNED NULL DEFAULT \'0\'',
            'money' => 'INT(10) UNSIGNED NOT NULL DEFAULT \'0\'',
            'description' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%tournament_rule}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'tournament_id' => 'INT(10) UNSIGNED NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'description' => 'TEXT NOT NULL DEFAULT \'\'',
        ], $tableOptions_mysql);

        $this->createTable('{{%tournament_to_player}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'tournament_id' => 'INT(10) UNSIGNED NOT NULL',
            'player_id' => 'INT(10) UNSIGNED NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%tournament_to_team}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'tournament_id' => 'INT(10) UNSIGNED NOT NULL',
            'team_id' => 'INT(10) UNSIGNED NOT NULL',
        ], $tableOptions_mysql);

        $this->createTable('{{%tournament_type}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'name' => 'VARCHAR(255) NOT NULL',
        ], $tableOptions_mysql);

        $this->addColumn('{{%user}}', 'avatar', $this->string(255)->defaultValue(null));
        $this->addColumn('{{%user}}', 'time_zone', $this->integer()->defaultValue(null));
        $this->addColumn('{{%user}}', 'language_id', $this->integer()->unsigned()->defaultValue(null));
        $this->execute('ALTER TABLE `user` ADD `marks` SET(\'courses\',\'discounts\',\'certificate\') NULL AFTER `language_id`;');
        $this->execute('ALTER TABLE `user` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->createIndex('idx_team_id', 'team_to_player', 'team_id', 0);
        $this->createIndex('idx_player_id', 'team_to_player', 'player_id', 0);
        $this->createIndex('idx_type_id', 'tournament', 'type_id', 0);
        $this->createIndex('idx_organizer_id', 'tournament', 'organizer_id', 0);
        $this->createIndex('idx_language_id', 'tournament', 'language_id', 0);
        $this->createIndex('idx_created_by', 'tournament', 'created_by', 0);
        $this->createIndex('idx_updated_by', 'tournament', 'updated_by', 0);
        $this->createIndex('idx_tournament_id', 'tournament_prize', 'tournament_id', 0);
        $this->createIndex('idx_tournament_id', 'tournament_rule', 'tournament_id', 0);
        $this->createIndex('idx_tournament_id', 'tournament_to_player', 'tournament_id', 0);
        $this->createIndex('idx_player_id', 'tournament_to_player', 'player_id', 0);
        $this->createIndex('idx_tournament_id', 'tournament_to_team', 'tournament_id', 0);
        $this->createIndex('idx_team_id', 'tournament_to_team', 'team_id', 0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_team-player_player', '{{%team_to_player}}', 'player_id', "`$dbName`.`player`", 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_team-player_team', '{{%team_to_player}}', 'team_id', "`$dbName`.`team`", 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_tournament_created', '{{%tournament}}', 'created_by', "`$dbName`.`user`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tournament_language', '{{%tournament}}', 'language_id', "`$dbName`.`language`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tournament_user', '{{%tournament}}', 'organizer_id', "`$dbName`.`user`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tournament_type', '{{%tournament}}', 'type_id', "`$dbName`.`tournament_type`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tournament_updated', '{{%tournament}}', 'updated_by', "`$dbName`.`user`", 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk_tournament-prize_tournament', '{{%tournament_prize}}', 'tournament_id', "`$dbName`.`tournament`", 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_tournament-rule_tournament', '{{%tournament_rule}}', 'tournament_id', "`$dbName`.`tournament`", 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_tourn-player_player', '{{%tournament_to_player}}', 'player_id', "`$dbName`.`player`", 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_tourn-player_tournament', '{{%tournament_to_player}}', 'tournament_id', "`$dbName`.`tournament`", 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_tourn-team_team', '{{%tournament_to_team}}', 'team_id', "`$dbName`.`team`", 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_tourn-team_tournament', '{{%tournament_to_team}}', 'tournament_id', "`$dbName`.`tournament`", 'id', 'CASCADE', 'RESTRICT');
        $this->execute('SET foreign_key_checks = 1;');

        $this->execute('SET foreign_key_checks = 0');
        $this->insert('{{%user}}', ['id' => '1', 'username' => 'admin', 'auth_key' => 'yvSsA3Vi3VahGCpQY00wSm1sE9eIL5Rl', 'password_hash' => '$2y$13$sl8Ei5kWo9OYSbcUVZN10.ospVi9sbLhY60fskz5vVWe9qE0NUF2C', 'password_reset_token' => '123', 'email' => 'admin@dev.loc', 'status' => '10', 'created_at' => '1632921490', 'updated_at' => '1633691227', 'verification_token' => 'tSWu34_1L60mEMFSJH84k66VAHyvaNyZ_1632921490', 'avatar' => '', 'time_zone' => '', 'language_id' => '', 'marks' => '']);
        $this->insert('{{%user}}', ['id' => '2', 'username' => 'organizer', 'auth_key' => 'x2GCAsTWL7F_z23lW49KU6oRX129NbTq', 'password_hash' => '$2y$13$4MbObed1jCx06LV5OW9KvO5NkbDS//jd2WLi6RdO5rX55cnAeHvhy', 'password_reset_token' => '321', 'email' => 'organizer@dev.loc', 'status' => '10', 'created_at' => '1633439505', 'updated_at' => '1633691148', 'verification_token' => 'E6d3s9G7dvbDJJLPcY7to5_KWEdv0-Eo_1633439505', 'avatar' => '/storage/images/user/img_615ff8e7ccf7b.jpg', 'time_zone' => '', 'language_id' => '', 'marks' => '']);
        $this->insert('{{%user}}', ['id' => '3', 'username' => 'root', 'auth_key' => 'cpWHGvE-g2gg3mo4In2Pdiy4cIXdEKkY', 'password_hash' => '$2y$13$FU2DCvS9RI.PcvO74ADLQeOeDBtHkseQtn7791ZfQ0RKeXbPziS0.', 'password_reset_token' => '111', 'email' => 'root@dev.loc', 'status' => '10', 'created_at' => '1633509361', 'updated_at' => '1633691289', 'verification_token' => 'F78gxfI7K4dSyWaZjOmvdYXHcnU48IEf_1633509361', 'avatar' => '/storage/images/user/img_615ff9036c0a4.jpg', 'time_zone' => '2', 'language_id' => '0', 'marks' => 'certificate']);
        $this->execute('SET foreign_key_checks = 1;');

        Yii::$app->runAction('rbac/init');
    }

    public function down()
    {
        echo "m211008_201442_dev_init cannot be reverted.\n";

        return false;
    }
}
