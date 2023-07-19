<?php

use yii\db\Migration;

/**
 * Class m211216_115102_last
 */
class m211216_115102_last extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('player_race', 'gender', $this->tinyInteger()->defaultValue(1));

        $this->execute('update tournament_type set team_mode = 0 where id = 2;');

        foreach (\common\models\PlayerRace::find()->orderBy('id')->all() as $race) {
            $female = new \common\models\PlayerRace();
            $female->name = $race->name;
            $female->avatar = $race->avatar;
            $female->gender = \common\models\PlayerRace::GENDER_FEMALE;
            $female->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211216_115102_last cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211216_075102_last cannot be reverted.\n";

        return false;
    }
    */
}
