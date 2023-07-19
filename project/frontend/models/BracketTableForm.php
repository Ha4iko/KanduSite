<?php

namespace frontend\models;

use Yii;

class BracketTableForm extends Bracket
{
    /**
     * @var bool
     */
    private $loadedColumns = 0;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return $this
     */
    public function initDefaultValues()
    {
        $this->bracket_type = static::TYPE_TABLE;
        $this->order = 0;
        $this->editable = 1;
        $this->editable_participants = 1;
        $this->editable_scores = 1;
        return $this;
    }


    /**
     * @return BracketTableColumn[]
     * @throws \yii\db\Exception
     */
    public function getBracketColumnsAppended()
    {
        $columns = $this->bracketTableColumns;

        if (count($columns) < 10) {
            $diff = 10 - count($columns);

            $maxOrder = (int) Yii::$app->db->createCommand(
                'SELECT max(`order`) + 1 as `max` FROM `bracket_table_column` WHERE bracket_id = :bracket_id' , [
                ':bracket_id' => $this->id,
            ])
                ->queryScalar();

            for ($i = 0; $i < $diff; $i++) {
                $column = new BracketTableColumn();
                $column->title = '';
                $column->bracket_id = $this->id;
                $column->column_type = 0;
                $column->active = 0;
                $column->order = $maxOrder;
                $columns[] = $column;

                $maxOrder++;
            }
            $column = new BracketTableColumn();
            $column->title = 'top';
            $column->bracket_id = $this->id+100000;
            $column->column_type = 0;
            $column->active = 1;
            $column->order = $maxOrder+100000;
            $columns[] = $column;
        }

        return $columns;
    }



    public function loadForm($data, $formName = null)
    {
        $data = $this->prepareData($data);

        $loaded = parent::load($data, $formName);
        // if ($loaded && $this->hasMethod('loadRelations')) {
        //     $this->loadRelations($data);
        // }
        return $loaded;
    }


    public function prepareData(array $data)
    {
        $classNameInData = 'BracketTableColumn';
        $dataModels = $data[$classNameInData] ?? [];
        foreach ($dataModels as $k => &$dataModel) {
            // if (!trim($dataModel['title'])) {
            //     unset($dataModels[$k]);
            //     continue;
            // }

            $dataModel['active'] = $this->checkboxToInteger('active', $dataModel);
            if ($dataModel['active']) $this->loadedColumns++;
        }
        $data[$classNameInData] = $dataModels;

        return $data;
    }

    public function hasDeletedColumns()
    {
        $wasCount = count(BracketTableColumn::findAll(['active' => 1, 'bracket_id' => $this->id]));
        return $this->loadedColumns < $wasCount;    
    }

    private function checkboxToInteger(string $checkboxAttribute, array $dataOfModel)
    {
        return key_exists($checkboxAttribute, $dataOfModel)
            ? ($dataOfModel[$checkboxAttribute] === 'on' ? 1 : 0)
            : 0;
    }

    public function saveForm($clone = false)
    {
        if (!$this->validate()) {
            return false;
        }

        return $this->save();
    }

}
