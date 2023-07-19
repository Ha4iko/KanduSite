<?php

namespace common\services\Bracket;

/**
 * Class RelegationGeneratorService
 * @package common\services\Bracket
 */
class RelegationGeneratorService {

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * Generate brackets
     *
     * @param $playersCount
     * @param bool $doubleElimination // todo
     * @param bool $thirdPlace
     * @return array
     */
    public function generate($playersCount, $doubleElimination = true, $thirdPlace = true) {
        $players = array_keys(array_fill(1, $playersCount, null));
        $n = count($players);

        // create main bracket
        $main = [];
        $duelsCount = $n / 2;
        $tour = 1;
        $min = $doubleElimination ? 1 : 2;
        while ($duelsCount >= $min) {
            $duels = [];
            for ($i = 0; $i < $duelsCount; $i++) {
                $duels[] = [
                    'tour' => $tour,
                    'player_1' => null,
                    'player_2' => null,
                    'winner' => null,
                    'looser' => null,
                    'moved' => null,
                    'is_final' => false
                ];
            }
            $main = array_merge($main, $duels);
            $tour++;
            $duelsCount = $duelsCount / 2;
        }

        // create defeat bracket
        $defeat = [];
        if ($doubleElimination) {
            $tour = 2;
            $duelsCount = $n / 2 / 2;
            while ($duelsCount >= 1) {
                for ($n = 0; $n < 2; $n++) {
                    $duels = [];
                    for ($i = 0; $i < $duelsCount; $i++) {
                        $duels[] = [
                            'tour' => $tour,
                            'player_1' => null,
                            'player_2' => null,
                            'winner' => null,
                            'looser' => null,
                            'moved' => false,
                            'is_final' => false
                        ];
                    }
                    $defeat = array_merge($defeat, $duels);
                    $tour++;
                }
                $duelsCount = $duelsCount / 2;
            }
        }

        // create grand final brackets
        $grand = array_filter([
            $doubleElimination || !$thirdPlace ? null : [
                'tour' => $tour++,
                'player_1' => null,
                'player_2' => null,
                'winner' => null,
                'looser' => null,
                'moved' => false,
                'is_final' => true
            ],
            [
                'tour' => $tour++,
                'player_1' => null,
                'player_2' => null,
                'winner' => null,
                'looser' => null,
                'moved' => false,
                'is_final' => true
            ],
            $doubleElimination ? [
                'tour' => $tour++,
                'player_1' => null,
                'player_2' => null,
                'winner' => null,
                'looser' => null,
                'moved' => false,
                'is_final' => true
            ] : null
        ]);

        // combine into the one array
        $duels = array_merge($main, $defeat, $grand);
        foreach ($duels as $i => &$duel) {
            $duel['index'] = $i;
        }

        // define bracket ranges
        $mainRange = [0, count($main) -1];
        $defeatRange = [count($main), count($duels) - ($doubleElimination || !$thirdPlace ? 3 : 4)];
        if ($doubleElimination) {
            $grandRange = [count($duels) - 2, count($duels) - 1];
        } else {
            $grandRange = [count($duels) - ($thirdPlace ? 2 : 1), count($duels) - 1];
        }

        if ($doubleElimination) {
            $duels[$grandRange[1] - 1]['winner_index'] = $duels[$grandRange[1]]['index'];
            $duels[$grandRange[1] - 1]['loser_index'] = $duels[$grandRange[1]]['index'];
        }

        // fill first tour with players
        $this->fill($duels, $players, $mainRange);

        // emulate player games and save relations
        $movedMain = true;
        $movedDefeat = true;
        while ($movedMain || $movedDefeat) {

            // wait until all games in then defeat bracket done
            $this->makeGames($duels);
            if ($doubleElimination) {
                $movedDefeat = $this->moveDefeat($duels, $defeatRange, $grandRange);
            } else {
                $movedDefeat = false;
            }

            $movedMain = $this->moveMain($duels, $mainRange, $defeatRange, $grandRange, $doubleElimination, $thirdPlace);

            if ($this->debug) {
                $this->print_brackets(
                    array_slice($duels, $mainRange[0], $mainRange[1] + 1),
                    array_slice($duels, $defeatRange[0], $defeatRange[1] - $defeatRange[0] + 1),
                    array_slice($duels, $grandRange[0], $grandRange[1] - $grandRange[0] + 1),
                );
            }
        }

        return $duels;
    }

    /**
     * Emulate game result
     *
     * @param $duels
     * @param $range
     */
    private function makeGames(&$duels) {
        foreach ($duels as $i => &$game) {
            if ($game['player_1'] && $game['player_2'] && !$game['moved']) {
                $winner = 1;
                $game['winner'] = $game['player_' . $winner];
                $game['looser'] = $game['player_' . ($winner === 1 ? 2 : 1)];
            }
        }
    }

    /**
     * Get next free cell in the range
     *
     * @param $duels
     * @param $range
     * @return array|null
     */
    private function findNextFree(&$duels, $range) {
        $next = null;
        foreach ($duels as $i => &$game) {
            if ($i < $range[0] || $i > $range[1]) continue;

                
            if (!$game['player_1']) {
                return [$i, 'player_1'];
            }
            if (!$game['player_2']) {
                return [$i, 'player_2'];
            }

                   
        }
        return null;
    }

    private function findNextFree1(&$duels, $range) {
        $next = null;
        foreach ($duels as $i => &$game) {
            if ($i < $range[0] || $i > $range[1]) continue;

                
            if (!$game['player_1']) {
                return [$i, 'player_1'];
            }

                   
        }
        return null;
    }

    
    private function findNextFree2(&$duels, $range) {
        $next = null;
        foreach ($duels as $i => &$game) {
            if ($i < $range[0] || $i > $range[1]) continue;

                
            if (!$game['player_2']) {
                return [$i, 'player_2'];
            }

                   
        }
        return null;
    }

    /**
     * Fill bracket with players (for first round)
     *
     * @param $duels
     * @param $players
     * @param $range
     * @return int
     */
    private function fill(&$duels, $players, $range) {
        foreach ($players as $player) {
            [$index, $field] = $this->findNextFree($duels, $range);
            $duels[$index][$field] = $player;
        }
        return count($players);
    }

    /**
     * Move players in the main bracket
     *
     * @param $duels
     * @param $mainRange
     * @param $defeatRange
     * @param $grandRange
     * @param $doubleElimination
     * @param $thirdPlace
     * @return int
     */
    private function moveMain(&$duels, $mainRange, $defeatRange, $grandRange, $doubleElimination, $thirdPlace) {
        $moved = 0;
        foreach ($duels as $i => &$game) {
            if ($i < $mainRange[0] || $i > $mainRange[1]) continue;
            // if game have winner and looser find the next free cells
            if ($game['winner'] && $game['looser'] && !$game['moved']) {
                [$index, $field] = $this->findNextFree($duels, $mainRange);
                [$index2, $field2] = $this->findNextFree($duels, $defeatRange);
                [$index3, $field3] = $this->findNextFree1($duels, $defeatRange);
                if (isset($duels[$index])) {
                    $duels[$index][$field] = $game['winner'];
                    $game['winner_index'] = $index;
                } else {
                    if ($doubleElimination) {
                        $game['winner_index'] = $duels[$grandRange[1] - 1]['index'];
                    } else {
                        $game['winner_index'] = $duels[$grandRange[1]]['index'];
                    }
                }
                if (isset($duels[$index2])) {
                    if($index2 < $defeatRange[0]+(($defeatRange[0]+1)/4)){
                        $duels[$index2][$field2] = $game['looser'];
                        $game['loser_index'] = $index2;
                    }
                    elseif($index3 >= ($defeatRange[0]+(($defeatRange[0]+1)/4)) && ($defeatRange[0]) + (($defeatRange[0]+1)/2) > $index2){
                        $duels[$index3][$field3] = $game['looser'];
                        $game['loser_index'] = $index3;
                    } 
                    elseif($index3 >= ($defeatRange[0]+(($defeatRange[0]+1)/2)+(($defeatRange[0]+1)/8)) 
                    && ($defeatRange[0]) + (($defeatRange[0]+1)/2) + (($defeatRange[0]+1)/4) > $index2 ){
                        $duels[$index3][$field3] = $game['looser'];
                        $game['loser_index'] = $index3;
                    }
                    /*
                    elseif($index3 >= ($defeatRange[0]) + (($defeatRange[0]+1)/2) + (($defeatRange[0]+1)/4) + (($defeatRange[0]+1)/16)
                    &&  ($defeatRange[0]) + (($defeatRange[0]+1)/2) + (($defeatRange[0]+1)/4) + (($defeatRange[0]+1)/16) + (($defeatRange[0]+1)/16) >= $index2 ){
                        $duels[$index3][$field3] = $game['looser'];
                        $game['loser_index'] = $index3;
                    }
                    */
                    elseif($index3 == 27 && $defeatRange[0] == 15){
                        $duels[28][$field3] = $game['looser'];
                        $game['loser_index'] = 28;
                    }
                    elseif($game['index'] == 28 && $defeatRange[0] == 31){
                        $duels[57][$field3] = $game['looser'];
                        $game['loser_index'] = 57;
                    }
                    elseif($game['index'] == 29 && $defeatRange[0] == 31){
                        $duels[58][$field3] = $game['looser'];
                        $game['loser_index'] = 58;
                    }
                    elseif($game['index'] == 30 && $defeatRange[0] == 31){
                        $duels[60][$field3] = $game['looser'];
                        $game['loser_index'] = 60;
                    }
                    elseif($game['index'] == 56 && $defeatRange[0] == 63){
                        $duels[115][$field3] = $game['looser'];
                        $game['loser_index'] = 115;
                    }
                    elseif($game['index'] == 57 && $defeatRange[0] == 63){
                        $duels[116][$field3] = $game['looser'];
                        $game['loser_index'] = 116;
                    }
                    elseif($game['index'] == 58 && $defeatRange[0] == 63){
                        $duels[117][$field3] = $game['looser'];
                        $game['loser_index'] = 117;
                    }
                    elseif($game['index'] == 59 && $defeatRange[0] == 63){
                        $duels[118][$field3] = $game['looser'];
                        $game['loser_index'] = 118;
                    }
                    elseif($game['index'] == 60 && $defeatRange[0] == 63){
                        $duels[121][$field3] = $game['looser'];
                        $game['loser_index'] = 121;
                    }
                    elseif($game['index'] == 61 && $defeatRange[0] == 63){
                        $duels[122][$field3] = $game['looser'];
                        $game['loser_index'] = 122;
                    }
                    elseif($game['index'] == 62 && $defeatRange[0] == 63){
                        $duels[124][$field3] = $game['looser'];
                        $game['loser_index'] = 124;
                    }
                    
                    

                    
                 

                } else {
                    if ($doubleElimination) {
                        $game['loser_index'] = null;
                    } elseif ($thirdPlace && ($i === $mainRange[1] || $i === $mainRange[1] - 1)) {
                        $game['loser_index'] = $duels[$grandRange[0]]['index'];
                    }
                }
                $game['moved'] = true;
                $moved++;
            }
        }
        return $moved;
    }

    /**
     * Move players in the defeat bracket
     *
     * @param $duels
     * @param $defeatRange
     * @param $grandRange
     * @return int
     */
    private function moveDefeat(&$duels, $defeatRange, $grandRange) {
        $moved = 0;
        foreach ($duels as $i => &$game) {
            if ($i < $defeatRange[0] || $i > $defeatRange[1]) continue;
            // if game have winner and looser find the next cell for the winner
            if ($game['winner'] && $game['looser'] && !$game['moved']) {
                [$index, $field] = $this->findNextFree($duels, $defeatRange);
                [$index2, $field2] = $this->findNextFree2($duels, $defeatRange);
                if (isset($duels[$index2])) {          
                    if($index2 >= ($defeatRange[0]+(($defeatRange[0]+1)/4)) && ($defeatRange[0]) + (($defeatRange[0]+1)/2) > $index2){
                        $duels[$index2][$field2] = $game['winner'];
                        $game['winner_index'] = $index2;
                        $game['loser_index'] = null;
                    } 
                    elseif($index2 >= ($defeatRange[0]+(($defeatRange[0]+1)/2)) + (($defeatRange[0]+1)/8)
                     && ($defeatRange[0]) + (($defeatRange[0]+1)/2)+ (($defeatRange[0]+1)/4) > $index2){
                        $duels[$index2][$field2] = $game['winner'];
                        $game['winner_index'] = $index2;
                        $game['loser_index'] = null;
                    }          
                    else{
                        $duels[$index][$field] = $game['winner'];
                        $game['winner_index'] = $index;
                        $game['loser_index'] = null;
                    }                     
                } else {
                    $game['winner_index'] = $duels[$grandRange[0]]['index'];
                }
                $game['moved'] = true;
                $moved++;
            }
        }
        return $moved;
    }


    private function print_brackets($main, $defeat, $grand) {
        echo "T\tP1\tP1\tW\tL\tMoved\tisF\tIdx\tWidx\tLidx\n";
        foreach ($main as $item1) {
            echo implode("\t", array_values($item1)) . "\n";
        }
        echo "\n";
        foreach ($defeat as $item2) {
            echo implode("\t", array_values($item2)) . "\n";
        }
        echo "\n";
        foreach ($grand as $item3) {
            echo implode("\t", array_values($item3)) . "\n";
        }
        echo "\n\n";
    }
}