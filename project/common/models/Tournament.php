<?php

namespace common\models;

use common\traits\ImageTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "tournament".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $status
 * @property int $pool
 * @property string $pool_custom
 * @property string|null $date
 * @property string|null $date_final
 * @property string|null $time
 * @property string|null $time_final
 * @property int|null $type_id
 * @property string|null $bg_image
 * @property int|null $organizer_id
 * @property int $language_id
 * @property int $created_by
 * @property int $updated_by
 * @property int $time_zone
 * @property string $prize_one
 * @property string $prize_two
 * @property string $prize_three
 * @property string $prize_four
 * @property int $schedule_type
 * @property int $show_on_main_page
 * @property int $is_primary
 *
 * @property User $organizer
 * @property Language $language
 * @property Player[] $players
 * @property TournamentPrize[] $tournamentPrizes
 * @property TournamentRule[] $tournamentRules
 * @property TournamentMedia[] $tournamentMedias
 * @property TournamentSchedule[] $tournamentSchedules
 * @property Team[] $teams
 * @property TournamentType $type
 * @property string $typeName
 * @property string $statusLabel
 * @property TournamentToPlayer[] $tournamentToPlayer
 * @property TournamentToTeam[] $tournamentToTeam
 */
class Tournament extends \yii\db\ActiveRecord
{
    use SaveRelationsTrait;
    use ImageTrait;

    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_DRAFT = 9;

    public static function getStatusLabels()
    {
        return [
            static::STATUS_PENDING => 'Pending',
            static::STATUS_IN_PROGRESS => 'In progress',
            static::STATUS_COMPLETED => 'Completed',
        ];
    }
    public static function getStatusLabelsBySlugs()
    {
        return [
            'in-progress' => 'In progress',
            'pending' => 'Pending',
            'completed' => 'Completed',
        ];
    }
    public static function getStatusIdsBySlugs()
    {
        return [
            'in-progress' => static::STATUS_IN_PROGRESS,
            'pending' => static::STATUS_PENDING,
            'completed' => static::STATUS_COMPLETED,
        ];
    }
    public static function getStatusIdBySlug($slug)
    {
        $slug = trim($slug);
        $idsBySlugs = static::getStatusIdsBySlugs();
        return isset($idsBySlugs[$slug]) ? $idsBySlugs[$slug] : null;
    }
    public function getStatusLabel()
    {
        $labels = static::getStatusLabels();
        return isset($labels[$this->status]) ? $labels[$this->status] : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['date', 'time'], 'required'],
            //[['date_final', 'time_final'], 'required'],

            [['organizer_id', 'language_id', 'type_id', 'time_zone'], 'required'],
            [['bg_image'], 'required'],

            [['prize_one', 'prize_two', 'prize_three', 'prize_four'], 'string', 'max' => 50],
            [['pool_custom'], 'string', 'max' => 20],

            [['status', 'pool', 'type_id', 'organizer_id', 'language_id', 'time_zone'], 'integer'],
            [['schedule_type', 'show_on_main_page', 'is_primary'], 'integer'],
            [['created_by', 'updated_by'], 'integer'],
            [['date', 'date_final'], 'date', 'format' => 'php:Y-m-d'],//php:Y-m-d H:i:s
            [['time', 'time_final'], 'time', 'format' => 'php:H:i:s'],
            [['title', 'bg_image', 'slug'], 'string', 'max' => 255],
            [['organizer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['organizer_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TournamentType::className(), 'targetAttribute' => ['type_id' => 'id']],

            [['teams', 'players', 'tournamentRules', 'tournamentPrizes'], 'safe'],
            [['tournamentToPlayer', 'tournamentToTeam'], 'safe'],
            [['tournamentMedias', 'tournamentSchedules'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'status' => 'Status',
            'pool' => 'Pool',
            'date' => 'Date',
            'date_final' => 'Finale date',
            'time' => 'Time',
            'timeFormatted' => 'Time',
            'time_final' => 'Finale time',
            'timeFinalFormatted' => 'Finale time',
            'type_id' => 'Type',
            'bg_image' => 'Background image',
            'organizer_id' => 'Organizer',
            'language_id' => 'Laguage',
            'teams' => 'Teams',
            'players' => 'Players',
            'slug' => 'Alias',
            'time_zone' => 'Time zone',
            'prize_one' => '1st place prize',
            'prize_two' => '2nd place prize',
            'prize_three' => '3rd place prize',
            'prize_four' => '4th place prize',
            'schedule_type' => 'Schedule type',
            'show_on_main_page' => 'Place on main page',
            'is_primary' => 'Is primary',
            'pool_custom' => 'Prize Pool',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
                ],
            ],
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
            ],
            'relations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'teams',
                    'players',
                    'tournamentRules',
                    'tournamentPrizes',
                    'tournamentMedias',
                    'tournamentToPlayer',
                    'tournamentToTeam',
                    'tournamentSchedules',
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::class, ['id' => 'team_id'])->via('tournamentToTeam');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentToTeam()
    {
        return $this->hasMany(TournamentToTeam::class, ['tournament_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::class, ['id' => 'player_id'])->via('tournamentToPlayer');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentToPlayer()
    {
        return $this->hasMany(TournamentToPlayer::class, ['tournament_id' => 'id']);
    }

    /**
     * Gets query for [[Organizer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizer()
    {
        return $this->hasOne(User::class, ['id' => 'organizer_id']);
    }

    /**
     * Gets query for [[TournamentPrizes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentPrizes()
    {
        return $this->hasMany(TournamentPrize::class, ['tournament_id' => 'id'])->orderBy([
            'id' => SORT_ASC,
        ]);
    }

    /**
     * Gets query for [[TournamentRules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentRules()
    {
        return $this->hasMany(TournamentRule::class, ['tournament_id' => 'id'])->orderBy([
            'order' => SORT_ASC,
            'id' => SORT_ASC,
        ]);
    }

    /**
     * Gets query for [[TournamentMedias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentMedias()
    {
        return $this->hasMany(TournamentMedia::class, ['tournament_id' => 'id']);
    }

    /**
     * Gets query for [[TournamentSchedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentSchedules()
    {
        return $this->hasMany(TournamentSchedule::class, ['tournament_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrackets()
    {
        return $this->hasMany(Bracket::class, ['tournament_id' => 'id']);
    }

    /**
     * @param integer $byTypeId
     * @return \yii\db\ActiveQuery
     */
    public function getBracketsByType($byTypeId)
    {
        return $this->hasMany(Bracket::class, ['tournament_id' => 'id'])
            ->where(['bracket_type' => $byTypeId]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TournamentType::class, ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $this->updatePrizePool();
    }

    private function updatePrizePool(){
        $pool = 0;

        $pool += ($prize1 = intval(trim($this->prize_one))) ? $prize1 : 0;
        $pool += ($prize2 = intval(trim($this->prize_two))) ? $prize2 : 0;
        $pool += ($prize3 = intval(trim($this->prize_three))) ? $prize3 : 0;
        $pool += ($prize4 = intval(trim($this->prize_four))) ? $prize4 : 0;

        foreach ($this->tournamentPrizes as $prize) {
            $pool += ($prizeMoney = intval(trim($prize->money))) ? $prizeMoney : 0;
        }

        Yii::$app->db->createCommand('update tournament set pool = :pool where id = :id', [
            ':pool' => $pool,
            ':id' => $this->id,
        ])->execute();
    }


    /**
     * @return array
     */
    public function getPlayerClassIds()
    {
        if ($this->type->team_mode) return [];

        $ids = [];
        foreach ($this->tournamentToPlayer as $ttp) {
            $ids[$ttp->id] = $ttp->class_id;
        }

        return $ids;
    }
}
