<?php

namespace common\helpers;

use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class DataTransformHelper extends Component
{
    /**
     * @param string $modelClass
     * @param string $attributeList
     * @param string $attributeKey
     * @param bool $nullCaption
     * @return array
     */
    public static function getList($modelClass, $attributeList, $attributeKey = 'id', $nullCaption = false)
    {
        $list = $modelClass::find()
            ->select($attributeList . ', ' . $attributeKey)
            ->indexBy($attributeKey)
            ->asArray()
            ->column();
        return is_string($nullCaption)
            ? ArrayHelper::merge([null => $nullCaption], $list)
            : $list;
    }
}