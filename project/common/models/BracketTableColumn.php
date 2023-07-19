<?php

namespace common\models;

use Yii;

/**
 * @property int $id
 * @property int $bracket_id
 * @property int $column_type
 * @property string $title
 * @property int $active
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 */
class BracketTableColumn extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_table_column';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title'], 'customRequired', 'skipOnEmpty' => false],
            [['bracket_id', 'column_type', 'active', 'order'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function customRequired($attribute, $params)
    {
        if ($this->active && !trim($this->title)) {
            $this->addError($attribute, 'Value cannot be blank.');
        }
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracket()
    {
        return $this->hasOne(Bracket::class, ['id' => 'bracket_id']);
    }
}
