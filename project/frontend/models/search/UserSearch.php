<?php

namespace frontend\models\search;

use frontend\models\Tournament;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\User;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form of User.
 *
 * @property string $role
 * @property string $order
 * @property string $maxRole
 * @property int $tournamentsCount
 */
class UserSearch extends User
{
    public $tournamentsCount = 0;

    /**
     * @var string
     */
    public $role = '';

    /**
     * @var string
     */
    public $order = 'date';

    /**
     * @var array
     */
    public static $orderList = [
        'date' => [
            'id' => '',
            'label' => 'added',
            'sort' => ['created_at' => SORT_DESC],
        ],
        'username' => [
            'id' => 'username',
            'label' => 'nickname',
            'sort' => ['username' => SORT_ASC],
        ],
        'count' => [
            'id' => 'count',
            'label' => 'created tournament',
            'sort' => ['tournamentsCount' => SORT_DESC],
        ],
    ];

    /**
     * Get order labels
     *
     * @return array
     */
    public static function getOrderLabels()
    {
        return ArrayHelper::map(static::$orderList, 'id', 'label');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['order', 'role'], 'string'];
        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['order'] = 'Sort by';
        $labels['role'] = 'Role';
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
        $query = static::find()
            ->select(['u.*', 'count(t.id) as tournamentsCount'])
            ->alias('u')
            ->groupBy('u.id')
            ->leftJoin(Tournament::tableName() . ' t', 't.organizer_id = u.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setPagination([
            'pageSize' => 10,
            'defaultPageSize' => 10,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (trim($this->role)) {
            $query->innerJoin('auth_assignment a', 'a.user_id = u.id');
            $query->andFilterWhere([
                'a.item_name' => trim($this->role),
            ]);
        }

        $query->andFilterWhere(['like', 'username', $this->username]);

        $query->orderBy(static::$orderList[$this->order]['sort']);

        return $dataProvider;
    }

    /**
     * @return string|null
     */
    public function getMaxRole()
    {
        if (!$this->id || $this->isNewRecord) return null;

        $authManager = Yii::$app->authManager;
        if ($authManager->checkAccess($this->id, 'root')) {
            return 'Superadmin';
        }
        if ($authManager->checkAccess($this->id, 'admin')) {
            return 'Admin';
        }
        if ($authManager->checkAccess($this->id, 'organizer')) {
            return 'Organizer';
        }

        return null;
    }

    // /**
    //  * @return string|null
    //  */
    // public function getTournamentsCount()
    // {
    //     if (!$this->id || $this->isNewRecord) return null;
    //
    //     $authManager = Yii::$app->authManager;
    //     if ($authManager->checkAccess($this->id, 'root')) {
    //         return 'Superadmin';
    //     }
    //     if ($authManager->checkAccess($this->id, 'admin')) {
    //         return 'Admin';
    //     }
    //     if ($authManager->checkAccess($this->id, 'organizer')) {
    //         return 'Organizer';
    //     }
    //
    //     return null;
    // }

}
