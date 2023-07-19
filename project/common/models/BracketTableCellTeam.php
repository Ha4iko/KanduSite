<?php

namespace common\models;

use Yii;

/**
 * @property int $id
 * @property int $bracket_table_column_id
 * @property int $bracket_table_row_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 */
class BracketTableCellTeam extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_table_cell_team';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['bracket_table_column_id', 'bracket_table_row_id', 'top'], 'integer'],
            [['value'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

}
