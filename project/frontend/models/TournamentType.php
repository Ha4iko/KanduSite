<?php

namespace frontend\models;

use yii\helpers\ArrayHelper;

class TournamentType extends \common\models\TournamentType
{
    /**
     * @return array
     */
    public static function getTypeNames()
    {
        $names = [];
        foreach (static::find()->all() as $type) {
            //if ($type->bsg) continue;
            $typeName = $type->name;
            if ($type->id == 1) $typeName .= ' (solo)';
            if ($type->id == 2) $typeName = '1 vs 1 5x5 (teams)';
            $names[$type->slug] = $typeName;
        }

        // $names['bsg'] = 'BSG';

        return $names;
    }

    /**
     * @return array
     */
    public static function getTypeNamesKeyId()
    {
        $names = [];
        foreach (static::find()->all() as $type) {
            $typeName = $type->name;
            if ($type->id == 1) $typeName .= ' (solo)';
            if ($type->id == 2) $typeName = '1 vs 1 5x5 (teams)';
            $names[$type->id] = $typeName;
        }

        return $names;
    }

    /**
     * @return static[]
     */
    public static function getTypesForHome()
    {
        $types = [];
        foreach (static::find()->all() as $type) {
            //if ($type->bsg) continue;

            $types[] = $type;
        }

        // $types[] = new static([
        //     'slug' => 'bsg',
        //     'name' => 'BSG',
        //     'description' => 'teams',
        // ]);

        return $types;
    }
}
