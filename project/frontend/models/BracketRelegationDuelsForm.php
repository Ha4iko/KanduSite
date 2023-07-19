<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\Bracket\Relegation;
use common\models\Bracket\Relegation\Duel;
use common\services\Bracket\RelegationService;
use common\models\Bracket\Relegation\Round;

/**
 * @property string $duelClassName
 *
 * @property array $bracketParticipantsIds
 * @property TournamentToPlayer[] $tournamentParticipantsPlayers
 * @property TournamentToTeam[] $tournamentParticipantsTeams
 * @property Tournament $tournament
 */
class BracketRelegationDuelsForm extends Model
{
    /**
     * @var string
     */
    public $duelClassName;

    /**
     * @var int
     */
    public $bracketId;

    /**
     * @var Tournament
     */
    public $tournament;

    /**
     * @var bool
     */
    private $isTeamMode;

    /**
     * @var array[]
     */
    public $duels;

    /**
     * @var Relegation
     */
    private $bracket;

    /**
     * @var RelegationService
     */
    private $relegationService;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        parent::init();
        $this->relegationService = Yii::$container->get(RelegationService::class);
        $this->bracket = Relegation::findOne($this->bracketId);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['duels'], 'validateItems']
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $debug = false; // потом удалим пусть пока побудет

        $transaction = Yii::$app->db->beginTransaction();
        try {
            /*-------------------------*/ if ($debug) echo "<pre>";
            foreach ($this->duels as $item) {
                // need always fresh model (changing in cycle)
                $duel = $this->bracket->getDuelById($item['id']);
                if (!$duel) continue;
                /*-------------------------*/ if ($debug) echo "id:{$duel->id}\n";

                // load post data: p - player, s - score
                $p1id = false; $p1act = '';
                if (key_exists('player_1', $item)) {
                    $p1id = intval($item['player_1']) ?: null;
                    if (!$duel->player_1) {
                        if ($p1id) $p1act = 'added';
                    } else {
                        if (!$p1id) {
                            $p1act = 'deleted';
                        } elseif ($p1id !== $duel->player_1) {
                            $p1act = 'changed';
                        }
                    }
                }

                $p2id = false; $p2act = '';
                if (key_exists('player_2', $item)) {
                    $p2id = intval($item['player_2']) ?: null;
                    if (!$duel->player_2) {
                        if ($p2id) $p2act = 'added';
                    } else {
                        if (!$p2id) {
                            $p2act = 'deleted';
                        } elseif ($p2id !== $duel->player_2) {
                            $p2act = 'changed';
                        }
                    }
                }

                $s1num = false; $s1act = '';
                if (key_exists('score_one', $item)) {
                    $s1num = is_numeric($item['score_one']) ? intval($item['score_one']) : null;
                    if ($s1num !== $duel->score_one) $s1act = 'changed';
                }

                $s2num = false; $s2act = '';
                if (key_exists('score_two', $item)) {
                    $s2num = is_numeric($item['score_two']) ? intval($item['score_two']) : null;
                    if ($s2num !== $duel->score_two) $s2act = 'changed';
                }


                // detected changed fields
                if ($p1act || $p2act || $s1act || $s2act) {
                    /*-------------------------*/ if ($debug) var_dump('--player1', $p1id, $p1act, '--player2', $p2id, $p2act);
                    /*-------------------------*/ if ($debug) var_dump('--score1', $s1num, $s1act, '--score2', $s2num, $s2act);

                    $duelOld = clone $duel; // for clearing removed players

                    if ($p1id !== false) $duel->player_1 = $p1id;
                    if ($p2id !== false) $duel->player_2 = $p2id;
                    if ($s1num !== false) $duel->score_one = $s1num;
                    if ($s2num !== false) $duel->score_two = $s2num;

                    $isValid = // need guarantee of successfully save after linking next duels
                        (intval($duel->player_1) || is_null($duel->player_1)) &&
                        (intval($duel->player_2) || is_null($duel->player_2)) &&
                        (is_int($duel->score_one) || is_null($duel->score_one)) &&
                        (is_int($duel->score_two) || is_null($duel->score_two));

                    if (!$isValid) {
                        throw new \Exception('Duel is invalid');
                    }
                    /*-------------------------*/ if ($debug) echo 'valid:' . intval($isValid) . "\n";

                    $wNext = $duel->getWinnerDuel();
                    $lNext = $duel->getLoserDuel();

                    // case: removed player(s)
                    if ($p1act == 'deleted' || $p2act == 'deleted') {
                        $duel->score_one = null;
                        $duel->score_two = null;
                        $duel->winner_id = null;
                        $duel->loser_id = null;

                        if ($wNext) {
                            if ($p1act == 'deleted') {
                                if ($wNext->player_1 == $duelOld->player_1) $wNext->player_1 = null;
                                if ($wNext->player_2 == $duelOld->player_1) $wNext->player_2 = null;
                            }
                            if ($p2act == 'deleted') {
                                if ($wNext->player_1 == $duelOld->player_2) $wNext->player_1 = null;
                                if ($wNext->player_2 == $duelOld->player_2) $wNext->player_2 = null;
                            }

                            if (!$wNext->save()) {
                                throw new \Exception('Duel not saved: ' . json_encode($wNext->getFirstErrors()));
                            }
                        }
                        if ($lNext) {
                            if ($p1act == 'deleted') {
                                if ($lNext->player_1 == $duelOld->player_1) $lNext->player_1 = null;
                                if ($lNext->player_2 == $duelOld->player_1) $lNext->player_2 = null;
                            }
                            if ($p2act == 'deleted') {
                                if ($lNext->player_1 == $duelOld->player_2) $lNext->player_1 = null;
                                if ($lNext->player_2 == $duelOld->player_2) $lNext->player_2 = null;
                            }

                            if (!$lNext->save()) {
                                throw new \Exception('Duel not saved: ' . json_encode($lNext->getFirstErrors()));
                            }
                        }
                    }

                    // case: added/changed player(s)
                    if ($p1act != 'deleted' && $p2act != 'deleted') {

                        $completed = $duel->player_1 && $duel->player_2 &&
                            (intval($duel->score_one) + intval($duel->score_two)) >= $this->bracket->best_of;

                        /*-------------------------*/ if ($debug) echo 'completed:' . intval($completed) . "\n";
                        //*-------------------------*/ if ($debug) var_dump($duel->attributes);
                        if ($completed) {
                            $duel->winner_id = $duel->score_one > $duel->score_two ? $duel->player_1 : $duel->player_2;
                            $duel->loser_id = $duel->winner_id === $duel->player_1 ? $duel->player_2 : $duel->player_1;


                            if ($wNext && $lNext && $wNext->id === $lNext->id) {

                                $wNext->player_1 = $duel->winner_id;
                                $wNext->player_2 = $duel->loser_id;
                                if (!$wNext->save()) {
                                    throw new \Exception('Duel not saved: ' . json_encode($wNext->getFirstErrors()));
                                }

                            } else {

                                // move winner to the next duel
                                if ($wNext) {
                                    if ($wNext->player_1 == $duelOld->player_1) $wNext->player_1 = null;
                                    if ($wNext->player_2 == $duelOld->player_1) $wNext->player_2 = null;
                                    if ($wNext->player_1 == $duelOld->player_2) $wNext->player_1 = null;
                                    if ($wNext->player_2 == $duelOld->player_2) $wNext->player_2 = null;
                                    if ($wNext->player_1 == $duel->player_1) $wNext->player_1 = null;
                                    if ($wNext->player_2 == $duel->player_1) $wNext->player_2 = null;
                                    if ($wNext->player_1 == $duel->player_2) $wNext->player_1 = null;
                                    if ($wNext->player_2 == $duel->player_2) $wNext->player_2 = null;

                                    if (!$wNext->player_1) {
                                        $wNext->player_1 = $duel->winner_id;
                                    } elseif (!$wNext->player_2) {
                                        $wNext->player_2 = $duel->winner_id;
                                    } else {
                                        throw new \Exception('abnormal winner case');
                                    }

                                    $wNext->score_one = null;
                                    $wNext->score_two = null;
                                    $wNext->winner_id = null;
                                    $wNext->loser_id = null;

                                    if (!$wNext->save()) {
                                        throw new \Exception('Duel not saved: ' . json_encode($wNext->getFirstErrors()));
                                    }
                                }

                                // move loser to the next duel
                                if ($lNext) {
                                    if ($lNext->player_1 == $duelOld->player_1) $lNext->player_1 = null;
                                    if ($lNext->player_2 == $duelOld->player_1) $lNext->player_2 = null;
                                    if ($lNext->player_1 == $duelOld->player_2) $lNext->player_1 = null;
                                    if ($lNext->player_2 == $duelOld->player_2) $lNext->player_2 = null;
                                    if ($lNext->player_1 == $duel->player_1) $lNext->player_1 = null;
                                    if ($lNext->player_2 == $duel->player_1) $lNext->player_2 = null;
                                    if ($lNext->player_1 == $duel->player_2) $lNext->player_1 = null;
                                    if ($lNext->player_2 == $duel->player_2) $lNext->player_2 = null;

                                    if (!$lNext->player_1) {
                                        $lNext->player_1 = $duel->loser_id;
                                    } elseif (!$lNext->player_2) {
                                        $lNext->player_2 = $duel->loser_id;
                                    } else {
                                        throw new \Exception('abnormal loser case');
                                    }

                                    $lNext->score_one = null;
                                    $lNext->score_two = null;
                                    $lNext->winner_id = null;
                                    $lNext->loser_id = null;

                                    if (!$lNext->save()) {
                                        throw new \Exception('Duel not saved: ' . json_encode($lNext->getFirstErrors()));
                                    }
                                }
                            }


                        } else {
                            $duel->winner_id = null;
                            $duel->loser_id = null;
                        }

                    }

                    if (!$duel->save()) {
                        throw new \Exception('Duel not saved: ' . json_encode($duel->getFirstErrors()));
                    }
                }
                /*-------------------------*/ if ($debug) echo "--------------- end duel ---------------\n";
            }
            /*-------------------------*/ if ($debug) echo "</pre>";
            /*-------------------------*/ if ($debug) exit;

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    // /**
    //  * @return bool
    //  * @throws \Exception
    //  */
    // public function save()
    // {
    //     if (!$this->validate()) {
    //         return false;
    //     }
    //     $duels = $this->bracket->getDuels();
    //     $duels = ArrayHelper::index($duels, 'id');
    //
    //     $transaction = Yii::$app->db->beginTransaction();
    //     try {
    //         $cleared = [];
    //         $changed = [];
    //
    //         foreach ($this->duels as $item) {
    //             /** @var Duel $duel */
    //             $duel = $duels[$item['id']];
    //             if (!$duel) {
    //                 continue;
    //             }
    //             $player_1_old = $duel->player_1;
    //             $score_one_old = $duel->score_one;
    //             $player_2_old = $duel->player_2;
    //             $score_two_old = $duel->score_two;
    //             $duel->load($item, '');
    //             $player_1 = intval($item['player_1']) ?: null;
    //             $player_2 = intval($item['player_2']) ?: null;
    //             $wasModified = false;
    //             if (key_exists('player_1', $item) && $player_1_old != $player_1) {
    //                 $duel->player_1 = $player_1;
    //                 $wasModified = true;
    //                 if ($player_1_old && $player_1) {
    //                     $changed[$duel->id][$player_1] = $player_1_old;
    //                 }
    //             }
    //             if (key_exists('player_2', $item) && $player_2_old != $player_2) {
    //                 $duel->player_2 = $player_2;
    //                 $wasModified = true;
    //                 if ($player_2_old && $player_2) {
    //                     $changed[$duel->id][$player_2] = $player_2_old;
    //                 }
    //             }
    //             if ($wasModified && (!$duel->player_1 || !$duel->player_2)) {
    //                 $duel->score_one = null;
    //                 $duel->score_two = null;
    //                 $duel->winner_id = null;
    //                 $duel->loser_id = null;
    //                 $cleared[$duel->id]['player_1'] = $player_1_old;
    //                 $cleared[$duel->id]['player_2'] = $player_2_old;
    //             } else {
    //                 $score_one = is_numeric($item['score_one']) ? intval($item['score_one']) : null;
    //                 $score_two = is_numeric($item['score_two']) ? intval($item['score_two']) : null;
    //                 if (key_exists('score_one', $item) && $score_one_old !== $score_one) {
    //                     $duel->score_one = $score_one;
    //                 }
    //                 if (key_exists('score_two', $item) && $score_two_old !== $score_two) {
    //                     $duel->score_two = $score_two;
    //                 }
    //
    //                 if (is_int($duel->score_one) && is_int($duel->score_two) && $duel->player_1 && $duel->player_2) {
    //                     $duel->winner_id = $duel->score_one > $duel->score_two ? $duel->player_1 : $duel->player_2;
    //                     $duel->loser_id = $duel->winner_id === $duel->player_1 ? $duel->player_2 : $duel->player_1;
    //                     //$duel->completed = true;
    //                 }
    //             }
    //
    //
    //             if (!$duel->save()) {
    //                 throw new \Exception('Duel not saved: ' . json_encode($duel->getFirstErrors()));
    //             }
    //         }
    //
    //         foreach ($this->duels as $item) {
    //             /** @var Duel $duel */
    //             $duel = $duels[$item['id']];
    //             if (!$duel) {
    //                 continue;
    //             }
    //             $duel->load($item, '');
    //
    //             if (isset($cleared[$duel->id])) {
    //                 $wNext = $duel->getWinnerDuel();
    //                 $lNext = $duel->getLoserDuel();
    //
    //                 if ($wNext) {
    //                     if ($wNext->player_1 == $cleared[$duel->id]['player_1']) $wNext->player_1 = null;
    //                     if ($wNext->player_2 == $cleared[$duel->id]['player_1']) $wNext->player_2 = null;
    //                     if ($wNext->player_1 == $cleared[$duel->id]['player_2']) $wNext->player_1 = null;
    //                     if ($wNext->player_2 == $cleared[$duel->id]['player_2']) $wNext->player_2 = null;
    //
    //                     if (!$wNext->save()) {
    //                         throw new \Exception('Duel not saved: ' . json_encode($wNext->getFirstErrors()));
    //                     }
    //                 }
    //                 if ($lNext) {
    //                     if ($lNext->player_1 == $cleared[$duel->id]['player_1']) $lNext->player_1 = null;
    //                     if ($lNext->player_2 == $cleared[$duel->id]['player_1']) $lNext->player_2 = null;
    //                     if ($lNext->player_1 == $cleared[$duel->id]['player_2']) $lNext->player_1 = null;
    //                     if ($lNext->player_2 == $cleared[$duel->id]['player_2']) $lNext->player_2 = null;
    //
    //                     if (!$lNext->save()) {
    //                         throw new \Exception('Duel not saved: ' . json_encode($lNext->getFirstErrors()));
    //                     }
    //                 }
    //
    //             } else {
    //
    //                 if (is_int($duel->score_one) && is_int($duel->score_two) && $duel->player_1 && $duel->player_2) {
    //
    //                     $wNext = $duel->getWinnerDuel();
    //                     $lNext = $duel->getLoserDuel();
    //
    //                     if ($wNext && $lNext && $wNext->id === $lNext->id) {
    //                         $wNext->player_1 = $duel->winner_id;
    //                         $wNext->player_2 = $duel->loser_id;
    //                         if (!$wNext->save()) {
    //                             throw new \Exception('Duel not saved: ' . json_encode($wNext->getFirstErrors()));
    //                         }
    //                     } else {
    //
    //                         // move winner to the next duel
    //                         if ($wNext) {
    //                             $loser_id = isset($changed[$duel->id][$duel->loser_id])
    //                                 ? $changed[$duel->id][$duel->loser_id] : $duel->loser_id;
    //                             $winner_id = isset($changed[$duel->id][$duel->winner_id])
    //                                 ? $changed[$duel->id][$duel->winner_id] : $duel->winner_id;
    //
    //                             if ($wNext->player_1 == $loser_id) $wNext->player_1 = null;
    //                             if ($wNext->player_2 == $loser_id) $wNext->player_2 = null;
    //                             if ($wNext->player_1 == $winner_id) $wNext->player_1 = null;
    //                             if ($wNext->player_2 == $winner_id) $wNext->player_2 = null;
    //
    //                             if (!$wNext->player_1) {
    //                                 $wNext->player_1 = $duel->winner_id;
    //                             } elseif (!$wNext->player_2) {
    //                                 $wNext->player_2 = $duel->winner_id;
    //                             } else {
    //                                 throw new \Exception('abnormal winner case');
    //                             }
    //
    //                             if (!$wNext->save()) {
    //                                 throw new \Exception('Duel not saved: ' . json_encode($wNext->getFirstErrors()));
    //                             }
    //                         }
    //
    //                         // move loser to the next duel
    //                         if ($lNext) {
    //                             if ($lNext->player_1 == $duel->loser_id) $lNext->player_1 = null;
    //                             if ($lNext->player_2 == $duel->loser_id) $lNext->player_2 = null;
    //                             if ($lNext->player_1 == $duel->winner_id) $lNext->player_1 = null;
    //                             if ($lNext->player_2 == $duel->winner_id) $lNext->player_2 = null;
    //
    //                             if (!$lNext->player_1) {
    //                                 $lNext->player_1 = $duel->loser_id;
    //                             } elseif (!$lNext->player_2) {
    //                                 $lNext->player_2 = $duel->loser_id;
    //                             } else {
    //                                 throw new \Exception('abnormal winner case');
    //                             }
    //
    //                             if (!$lNext->save()) {
    //                                 throw new \Exception('Duel not saved: ' . json_encode($lNext->getFirstErrors()));
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         $transaction->commit();
    //         return true;
    //     } catch (\Exception $e) {
    //         $transaction->rollBack();
    //         throw $e;
    //     }
    // }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        foreach ($this->duels ?? [] as $i => &$item) {
            foreach (['id', 'score_one', 'score_two', 'player_1', 'player_2'] as $field) {
                if (is_numeric($item[$field])) {
                    $item[$field] = intval($item[$field]);
                } else {
                    unset($item[$field]);
                }
                $this->duels[$i] = $item;
            }
        }
        return parent::beforeValidate();
    }

    /**
     * @param $attr
     */
    public function validateItems($attr) {
        $items = $this->$attr;
        foreach ($items as $item) {
            if (!$item['id'] || !is_int($item['id'])) {
                $this->addError('duels', 'Duels id is not valid');
                break;
            }
            if (isset($item['score_one']) && !is_int($item['score_one'])) {
                $this->addError('duels', 'Duels score_one is not valid');
                break;
            }
            if (isset($item['score_two']) && !is_int($item['score_two'])) {
                $this->addError('duels', 'Duels score_two is not valid');
                break;
            }
            if (isset($item['score_one']) && isset($item['score_two']) && $item['score_one'] + $item['score_two'] > $this->bracket->best_of) {
                $this->addError('duels', 'Invalid count of games. Max: ' . $this->bracket->best_of);
                break;
            }
        }
    }

    /**
     * @return array
     */
    public function getParticipantsNames()
    {
        return ArrayHelper::merge(
            [null => ''],
            $this->relegationService->getBracketParticipantsList($this->bracket->id)
        );
    }
}
