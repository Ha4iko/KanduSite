<?php

namespace frontend\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use frontend\models\Media;
use yii\helpers\ArrayHelper;

/**
 * MediaSearch represents the model behind the search form of Media.
 *
 * @property string $order
 * @property string $type
 */
class MediaSearch extends Media
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $order = 'date';

    /**
     * @var array
     */
    public static $orderList = [
        'date' => [
            'id' => 'date',
            'label' => 'date',
            'sort' => ['date' => SORT_DESC],
        ],
        'title' => [
            'id' => 'title',
            'label' => 'title',
            'sort' => ['title' => SORT_ASC],
        ],
    ];

    /**
     * @return array
     */
    public static function getOrderLabels()
    {
        return ArrayHelper::map(static::$orderList, 'id', 'label');
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        unset($rules['required']);
        $rules[] = [['order', 'type'], 'safe'];
        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['type'] = 'Type';
        $labels['order'] = 'Sort by';
        return $labels;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param null|string $formName
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = static::find();
        if (!Yii::$app->user->can('root')) {
            $query->where(['active' => 1]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setPagination([
            'pageSize' => 10,
            'defaultPageSize' => 10,
        ]);

        $this->load($params, $formName);
        // var_dump($this->type);
        // var_dump($this->order);
        // var_dump($this->attributes);

        $published = $params['published'] ?? null;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (trim($this->title)) {
            $query->andFilterWhere([
                'OR',
                ['like', 'title', trim($this->title)],
                ['like', 'content', trim($this->title)]
            ]);
        }
        $query->andFilterWhere(['date' => $this->date]);

        if ($this->type) $query->andWhere(['is_' . $this->type => 1]);

        $query->orderBy(static::$orderList[$this->order]['sort']);

        // var_dump($query->createCommand()->rawSql);
        // exit;

        return $dataProvider;
    }


}
