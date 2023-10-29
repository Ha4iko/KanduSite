<?php

namespace frontend\service\export;

use common\models\Bracket\Relegation;
use frontend\models\Bracket;
use frontend\models\Tournament;
use Yii;

class TournamentExport
{
	
	/** \yii\db\Connection */
	private $db;
	
	/** @var array */
	private $classMap = [];
	
	/** @var array */
	private $factionMap = [];
	
	/** @var array */
	private $raceMap = [];
	
	/** @var array */
	private $worldMap = [];
	
	/** @var array */
	private $players = [];
	
	/** @var Tournament */
	private $tournament;
	
	/** @var array */
	private $brackets = [];
	
	public function __construct()
	{
		$this->db = Yii::$app->db;
	}
	
	public function run(Tournament $tournament): void
	{		
		$this->tournament = $tournament;
		$this->loadClassesMap();
		$this->loadFactionsMap();
		$this->loadRaceMap();
		$this->loadWorldMap();
		$this->loadPlayersMap();
				
		$this->loadBrackets();
		$this->loadRounds();
		
		$result = [
			'title' => $tournament->title,
			'status' => $tournament->status,
			'brackets' => array_values($this->brackets),
			'partisipants' => $this->getPartisipants(),
		];
		
		$res = file_put_contents(
			Yii::getAlias('@webroot') . '/assets/' . $tournament->slug . '.json', 
			json_encode($result, JSON_UNESCAPED_UNICODE)
		);
		
		if ($res !== false) {
			echo 'SUCCESS UPDATE /assets/' . $tournament->slug . '.json';
		} else {
			echo 'ERROR UPDATE /assets/' . $tournament->slug . '.json';
		}
	}
	
	private function loadClassesMap(): void
	{
		$res = $this->db->createCommand("SELECT * FROM player_class");
		
		foreach ($res->queryAll() as $row)
		{
			$this->classMap[(int) $row['id']] = $row;
		}
	}
	
	private function loadFactionsMap(): void
	{
		$res = $this->db->createCommand("SELECT * FROM player_faction");
		
		foreach ($res->queryAll() as $row)
		{
			$this->factionMap[(int) $row['id']] = $row;
		}
	}
	
	private function loadRaceMap(): void
	{
		$res = $this->db->createCommand("SELECT * FROM player_race");
		
		foreach ($res->queryAll() as $row)
		{
			$this->raceMap[(int) $row['id']] = $row;
		}
	}
	
	private function loadWorldMap(): void
	{
		$res = $this->db->createCommand("SELECT * FROM player_world");
		
		foreach ($res->queryAll() as $row)
		{
			$this->worldMap[(int) $row['id']] = $row;
		}
	}
	
	private function loadPlayersMap(): void
	{
		$res = $this->db->createCommand(
			"SELECT 
				tp.*, 
				p.nick,
				p.avatar,
				p.external_link
			FROM 
				tournament_to_player as tp
			LEFT JOIN 
				player as p ON(p.id = tp.player_id)
			WHERE 
				tournament_id = :tournament_id",
			[':tournament_id' => $this->tournament->id]
		);
		
		foreach ($res->queryAll() as $row)
		{
			$this->players[(int) $row['id']] = $row;
		}
	}
	
	private function loadBrackets(): void
	{
		$res = $this->db->createCommand(
			"SELECT * FROM bracket WHERE tournament_id = :tournament_id",
			[':tournament_id' => $this->tournament->id]
		);
		
		foreach ($res->queryAll() as $row)
		{					
			$this->brackets[(int) $row['id']] = [
				'id' => (int) $row['id'],
				'type' => (int) $row['bracket_type'],
				'title' => (string) $row['title'],
				'second_defeat' => (int) $row['second_defeat'],
				'rounds' => [],
			];
		}
	}
	
	private function loadRounds(): void
	{
		$this->loadSwissRounds();
		$this->loadGroupRounds();
		$this->loadFinalRounds();
		$this->loadStandingRounds();
	}
	
	private function loadSwissRounds(): void
	{
		if (count($this->brackets) === 0) {
			return;
		}
		
		$duels = $this->loadSwissDuels();
		$standings = $this->getSwissStandings($duels);
		
		$res = $this->db->createCommand(
			"SELECT 
				r.*,
				b.tournament_id
			FROM bracket_swiss_round  as r
			LEFT JOIN bracket as b ON(b.id = r.bracket_id)
			WHERE 
				b.tournament_id = :tournament_id
			ORDER BY
				r.order ASC, 
				r.id ASC",
			[':tournament_id' => $this->tournament->id]
		);
		
		foreach ($res->queryAll() as $row)
		{
			$key = (int) $row['bracket_id'];
			
			if (array_key_exists($key, $this->brackets) === false) {
				continue;
			}
			
			$this->brackets[$key]['rounds'][] = [
				"order" => (int) $row['order'],
				"title" => (string) $row['title'],
				"duels" => $duels[(int) $row['id']] ?? [],
			];
			
			$this->brackets[$key]['standings'] = $standings[$key] ?? [];
		}
	}
		
	private function loadSwissDuels(): array
	{
//		$teamsMode = $this->tournament->type->team_mode;
//		$this->loadSwissTeamDuels();
		
		return $this->loadSwissPlayerDuels();
	}
	
	private function loadSwissTeamDuels(): array
	{
		return [];
	}
	
	private function loadSwissPlayerDuels(): array
	{
		$result = [];
		
		$res = $this->db->createCommand(
			"SELECT 
				d.*,
				r.bracket_id
			FROM bracket_swiss_player_duel as d
			LEFT JOIN bracket_swiss_round as r ON(r.id = d.round_id)
			LEFT JOIN bracket as b ON(b.id = r.bracket_id)
			WHERE 
				b.tournament_id = :tournament_id
			ORDER BY
				d.order ASC,
				d.id ASC",
			[':tournament_id' => $this->tournament->id]
		);
		
		foreach ($res->queryAll() as  $row)
		{
			$key = (int) $row['round_id'];
			
			if (array_key_exists($key, $result) === false) {
				$result[$key] = []; 
			}
			
			$player1 = $this->players[(int) $row['player_one_id']] ?? [];
			$player2 = $this->players[(int) $row['player_two_id']] ?? [];
			
			$result[$key][] = [
				"id" => (int) $row['id'],
				"bracket_id" => (int) $row['bracket_id'],
				"round_id" => (int) $row['round_id'],
				"order" => (int) $row['order'],
				"active" => (int) $row['active'],
				'tie' => empty($row['winner_id']), // ничья
				"player1" => [
					"id" => (int) ($row['player_one_id'] ?? 0),
					"score" => (is_null($row['score_one']) ? null : (int) $row['score_one']),
					"scheme" => (is_null($row['scheme_one']) ? null : (int) $row['scheme_one']),
					"points" => (is_null($row['points_one']) ? null : (int) $row['points_one']),
					"winner" =>((int) $row['winner_id'] === (int) $row['player_one_id']),
					"loser" =>((int) $row['loser_id'] === (int) $row['player_one_id']),
					"name" => (string) ($player1['nick'] ?? ''),
					"color" => (empty($player1) ? '' : (string) $this->classMap[$player1['class_id']]['avatar']),
				],
				"player2" => [
					"id" => (int) ($row['player_two_id'] ?? 0),
					"score" => (is_null($row['score_two']) ? null : (int) $row['score_two']),
					"scheme" => (is_null($row['scheme_two']) ? null : (int) $row['scheme_two']),
					"points" => (is_null($row['points_two']) ? null : (int) $row['points_two']),
					"winner" =>((int) $row['winner_id'] === (int) $row['player_two_id']),
					"loser" =>((int) $row['loser_id'] === (int) $row['player_two_id']),
					"name" => (string) ($player2['nick'] ?? ''),
					"color" => (empty($player2) ? '' : (string) $this->classMap[$player2['class_id']]['avatar']),
				],
			];
		}
		
		return $result;
	}
	
	private function getSwissStandings(array $duels): array
	{
		$result = [];
		
		foreach ($duels as $round) {
			foreach ($round as $row) {
				$key = $row['bracket_id'];
				
				if (array_key_exists($key, $result) === false) {
					$result[$key] = []; 
				}
				
				if (array_key_exists($row['player1']['id'], $result[$key]) === false) {
					$result[$key][$row['player1']['id']] = [
						'id' => $row['player1']['id'],
						'name' => $row['player1']['name'],
						'color' => $row['player1']['color'],
						'play' => 0,
						'win' => 0,
						'lose' => 0,
						'tie' => 0,
						'points' => 0,
					]; 
				}
				
				if (array_key_exists($row['player2']['id'], $result[$key]) === false) {
					$result[$key][$row['player2']['id']] = [
						'id' => $row['player2']['id'],
						'name' => $row['player2']['name'],
						'color' => $row['player2']['color'],
						'play' => 0,
						'win' => 0,
						'lose' => 0,
						'tie' => 0,
						'points' => 0,
					]; 
				}
				
				$player1 = &$result[$key][$row['player1']['id']];
				$player2 = &$result[$key][$row['player2']['id']];
				
				if ($row['active'] === 0) {
					$player1['play'] += 1;
					$player2['play'] += 1;
					
					if ($row['tie']) { // ничья
						$player1['tie'] += 1;
						$player1['points'] += 1;
						$player2['tie'] += 1;
						$player2['points'] += 1;
					} else if ($row['player1']['winner']) {
						$player1['win'] += 1;
						$player1['points'] += 3;
						$player2['lose'] += 1;
					} else if ($row['player2']['winner']) {
						$player2['win'] += 1;
						$player2['points'] += 3;
						$player1['lose'] += 1;
					}
				 } else if ($row['active'] === 1) {
					 if ($row['player1']['id'] && $row['player2']['id'] === 0) {
						 $player1['points'] += 3;
					 } else if ($row['player2']['id'] && $row['player1']['id'] === 0) {
						 $player2['points'] += 3;
					 }
				 }
			}
		}
		
		foreach ($result as &$bracket)
		{
			$bracket = array_values($bracket);
			
			array_multisort(
				array_column($bracket, 'points'),
				SORT_DESC,
				array_column($bracket, 'name'),
				SORT_ASC,
				$bracket
			);
		}
		
		return $result;
	}
	
	private function loadGroupRounds(): void
	{
		if (count($this->brackets) === 0) {
			return;
		}
		
		$duels = $this->getGroupPlayerDuel();
		$bracketGroups = $this->getBracketGroupsMap();
		$standings = $this->getGroupStandings($bracketGroups, $duels);
		
		$res = $this->db->createCommand(
			"SELECT 
				gr.*,
				b.tournament_id
			FROM bracket_group_round  as gr
			LEFT JOIN bracket as b ON(b.id = gr.bracket_id)
			WHERE 
				b.tournament_id = :tournament_id
			ORDER BY
				gr.order ASC, 
				gr.id ASC",
			[':tournament_id' => $this->tournament->id]
		);
		
		foreach ($res->queryAll() as $row)
		{
			$key = (int) $row['bracket_id'];
			
			if (array_key_exists($key, $this->brackets) === false) {
				continue;
			}
			
			$this->brackets[$key]['rounds'][] = [
				"order" => (int) $row['order'],
				"title" => (string) $row['title'],
				"groups" => $this->fillRoundGroups(
					(int) $row['id'], 
					($bracketGroups[$key] ?? []), 
					($duels[$key][(int) $row['id']] ?? [])
				),
			];
			
			$this->brackets[$key]['standings'] = $standings[$key] ?? [];
		}
	}
	
	private function getBracketGroupsMap(): array
	{
		if (count($this->brackets) === 0) {
			return [];
		}
		
		$result = [];
		
		$res = $this->db->createCommand(
			"SELECT 
				gg.*,
				b.tournament_id
			FROM bracket_group_group  as gg
			LEFT JOIN bracket as b ON(b.id = gg.bracket_id)
			WHERE 
				b.tournament_id = :tournament_id
			ORDER BY
				gg.order ASC, 
				gg.id ASC",
			[':tournament_id' => $this->tournament->id]
		);
		
		foreach ($res->queryAll() as $row)
		{
			$key = (int) $row['bracket_id'];
			
			if (array_key_exists($key, $this->brackets) === false) {
				continue;
			}
			
			$result[$key][(int) $row['id']] = $row;
		}
		
		return $result;
	}
	
	private function getGroupPlayerDuel():  array
	{
		$result = [];
		
		$res = $this->db->createCommand(
			"SELECT 
				gd.*,
				r.bracket_id
			FROM bracket_group_player_duel as gd
			LEFT JOIN bracket_group_round as r ON(r.id = gd.round_id)
			LEFT JOIN bracket as b ON(b.id = r.bracket_id)
			WHERE 
				b.tournament_id = :tournament_id
			ORDER BY
				gd.order ASC,
				gd.id ASC",
			[':tournament_id' => $this->tournament->id]
		);
		
		foreach ($res->queryAll() as  $row)
		{			
			$bracketKey = (int) $row['bracket_id'];
			$key = (int) $row['round_id'];
			
			if (array_key_exists($bracketKey, $result) === false) {
				$result[$bracketKey] = []; 
			}
			
			if (array_key_exists($key, $result[$bracketKey]) === false) {
				$result[$bracketKey][$key] = []; 
			}
			
			$player1 = $this->players[(int) $row['player_one_id']] ?? [];
			$player2 = $this->players[(int) $row['player_two_id']] ?? [];
			
			$result[$bracketKey][$key][] = [
				"id" => (int) $row['id'],
				"bracket_id" => (int) $row['bracket_id'],
				"round_id" => (int) $row['round_id'],
				"group_id" => (int) $row['group_id'],
				"order" => (int) $row['order'],
				"active" => (int) $row['active'],
				'tie' => empty($row['winner_id']), // ничья
				"player1" => [
					"id" => (int) ($row['player_one_id'] ?? 0),
					"score" => (is_null($row['score_one']) ? null : (int) $row['score_one']),
					"scheme" => (is_null($row['scheme_one']) ? null : (int) $row['scheme_one']),
					"points" => (is_null($row['points_one']) ? null : (int) $row['points_one']),
					"winner" =>((int) $row['winner_id'] === (int) $row['player_one_id']),
					"loser" =>((int) $row['loser_id'] === (int) $row['player_one_id']),
					"name" => (string) ($player1['nick'] ?? ''),
					"color" => (empty($player1) ? '' : (string) $this->classMap[$player1['class_id']]['avatar']),
				],
				"player2" => [
					"id" => (int) ($row['player_two_id'] ?? 0),
					"score" => (is_null($row['score_two']) ? null : (int) $row['score_two']),
					"scheme" => (is_null($row['scheme_two']) ? null : (int) $row['scheme_two']),
					"points" => (is_null($row['points_two']) ? null : (int) $row['points_two']),
					"winner" =>((int) $row['winner_id'] === (int) $row['player_two_id']),
					"loser" =>((int) $row['loser_id'] === (int) $row['player_two_id']),
					"name" => (string) ($player2['nick'] ?? ''),
					"color" => (empty($player2) ? '' : (string) $this->classMap[$player2['class_id']]['avatar']),
				],
			];
		}
		
		return $result;
	}
	
	private function fillRoundGroups(int $round, array $groups, array $duels): array
	{
		$result = [];
		
		foreach($groups as $group)
		{
			$result[(int) $group['id']]  = [
				'order' => (int) $group['order'],
				'title' => (string) $group['title'],
				'duels' => [],
			];
		}
		
		foreach($duels as $row)
		{			
			$result[$row['group_id']]['duels'][] = $row;
		}
		
		$result = array_values($result);
		
		array_multisort(
			array_column($result, 'order'), 
			SORT_ASC,
			$result
		);
		
		return $result;
	}
	
	private function getGroupStandings(array $groups, array $duels): array
	{ 
		$result = [];
		
		foreach ($duels as $bracket => $rows)
		{
			$result[$bracket] = $this->buildBracketGroupStandings($groups[$bracket], $rows);
		}
		
		return $result;
	}
	
	private function buildBracketGroupStandings(array $group, array $duels): array
	{
		$result = [];
		
		foreach($group as $group)
		{
			$result[(int) $group['id']]  = [
				'order' => (int) $group['order'],
				'title' => (string) $group['title'],
				'standings' => [],
			];
		}
		
		foreach ($duels as $round) {
			foreach ($round as $row) {
				$key = $row['group_id'];
				
				if ($row['player1']['id'] > 0 && array_key_exists($row['player1']['id'], $result[$key]) === false) {
					$result[$key]['standings'][$row['player1']['id']] = [
						'id' => $row['player1']['id'],
						'name' => $row['player1']['name'],
						'color' => $row['player1']['color'],
						'play' => 0,
						'win' => 0,
						'lose' => 0,
						'tie' => 0,
						'points' => 0,
					];
				}
				
				if ($row['player2']['id'] > 0 && array_key_exists($row['player2']['id'], $result[$key]) === false) {
					$result[$key]['standings'][$row['player2']['id']] = [
						'id' => $row['player2']['id'],
						'name' => $row['player2']['name'],
						'color' => $row['player2']['color'],
						'play' => 0,
						'win' => 0,
						'lose' => 0,
						'tie' => 0,
						'points' => 0,
					]; 
				}
				
				$player1 = &$result[$key]['standings'][$row['player1']['id']];
				$player2 = &$result[$key]['standings'][$row['player2']['id']];
				
				if ($row['active'] === 0) {
					$player1['play'] += 1;
					$player2['play'] += 1;
					
					if ($row['tie']) { // ничья
						$player1['tie'] += 1;
						$player1['points'] += 1;
						$player2['tie'] += 1;
						$player2['points'] += 1;
					} else if ($row['player1']['winner']) {
						$player1['win'] += 1;
						$player1['points'] += 3;
						$player2['lose'] += 1;
					} else if ($row['player2']['winner']) {
						$player2['win'] += 1;
						$player2['points'] += 3;
						$player1['lose'] += 1;
					}
				 } 
				 
//				 if ($row['active'] === 1) {
//					 if ($row['player1']['id'] && $row['player2']['id'] === 0) {
//						 $player1['points'] += 3;
//					 } else if ($row['player2']['id'] && $row['player1']['id'] === 0) {
//						 $player2['points'] += 3;
//					 }
//				 }
			}
		}
		
		foreach ($result as &$group)
		{
			$group['standings'] = array_values($group['standings']);
			$group['standings'] = array_filter($group['standings']);
			
			array_multisort(
				array_column($group['standings'], 'points'),
				SORT_DESC,
				array_column($group['standings'], 'name'),
				SORT_ASC,
				$group['standings']
			);
		}
		
		$result = array_values($result);
		array_multisort(
			array_column($result, 'order'),
			SORT_ASC,
			$result
		);
		
		return $result;
	}
	
	private function loadFinalRounds(): void
	{
		foreach ($this->brackets as &$bracket)
		{
			if ($bracket['type'] !== Bracket::TYPE_RELEGATION) {
				continue;
			}
			
			$bracket['second_defeat'] = 1;
			$bracket['roundsMain'] =  $this->getFinalMainBrackets($bracket['id']);
			$bracket['roundsDefeat'] = $this->getFinalDefeatBrackets($bracket['id']);
			$bracket['roundsGrand'] = $this->getFinalGrandBrackets($bracket['id']);
			
			if (empty($bracket['roundsDefeat'])) {
				$bracket['second_defeat'] = 0;
				unset($bracket['roundsDefeat']);
			}
		}
	}
	
	private function getFinalMainBrackets(int $bracked): array
	{
		$asciiFromCode = 64;
		$rounds = $this->getFinalRounds($bracked, Relegation\Round::TYPE_MAIN);
		$duels = $this->getFinalBracketTypeDuels($bracked, Relegation\Round::TYPE_MAIN, $asciiFromCode);
		
		foreach ($duels as $row) {
			$index = count($rounds[$row['round_id']]['duels']) + 1;
			$row['code'] .= $index;
			
			$rounds[$row['round_id']]['duels'][] = $row;
		}
		
		return [
			'title' => 'Main bracket',
			'rounds' => array_values($rounds),
		];
	}
	
	private function getFinalDefeatBrackets(int $bracked): array
	{
		$asciiFromCode = 79;
		$rounds = $this->getFinalRounds($bracked, Relegation\Round::TYPE_DEFEAT);
		$duels = $this->getFinalBracketTypeDuels($bracked, Relegation\Round::TYPE_DEFEAT, $asciiFromCode);
		
		foreach ($duels as $row) {
			$index = count($rounds[$row['round_id']]['duels']) + 1;
			$row['code'] .= $index;
			
			$rounds[$row['round_id']]['duels'][] = $row;
		}
		
		return [
			'title' => 'Defeat bracket',
			'rounds' => array_values($rounds),
		];
	}
	
	private function getFinalGrandBrackets(int $bracked): array
	{
		$asciiFromCode = 87;
		$rounds = $this->getFinalRounds($bracked, Relegation\Round::TYPE_GRAND);
		$duels = $this->getFinalBracketTypeDuels($bracked, Relegation\Round::TYPE_GRAND, $asciiFromCode);
		
		foreach ($duels as $row) {
			$index = count($rounds[$row['round_id']]['duels']) + 1;
			$row['code'] .= $index;
			
			$rounds[$row['round_id']]['duels'][] = $row;
		}
		
		return [
			'title' => 'Grand final',
			'rounds' => array_values($rounds),
		];
	}
	
	private function getFinalRounds(int $bracked, int $type): array
	{
		$result = [];
		
		$res = $this->db->createCommand(
			"SELECT 
				* 
			FROM 
				bracket_relegation_round
			WHERE 
				bracket_id = :bracket_id
				AND type_id = :type_id
			ORDER BY
				level ASC",
			[':bracket_id' => $bracked, ':type_id' => $type]
		);
		
		foreach ($res->queryAll() as  $row)
		{			
			$result[(int) $row['id']] = [
				'title' => (string) $row['title'],
				'level' => (int) $row['level'],
				'duels' => [],
			];
		}
		
		return $result;
	}
	
	private function getFinalBracketTypeDuels(int $bracked, int $type, int $code): array
	{
		$result = [];
		
		$res = $this->db->createCommand(
			"SELECT 
				rd.*,
				rr.bracket_id,
				rr.type_id
			FROM 
				bracket_relegation_player_duel as rd
			LEFT JOIN
				bracket_relegation_round as rr ON(rr.id = rd.round_id)
			WHERE 
				rr.bracket_id = :bracket_id
				AND rr.type_id = :type_id
			ORDER BY
				rd.level ASC,
				rd.order ASC",
			[':bracket_id' => $bracked, ':type_id' => $type]
		);
		
		foreach ($res->queryAll() as  $row)
		{			
			$player1 = $this->players[(int) $row['player_one_id']] ?? [];
			$player2 = $this->players[(int) $row['player_two_id']] ?? [];
			
			$result[] = [
				"id" => (int) $row['id'],
				"round_id" => (int) $row['round_id'],
				'code' => chr($code + (int) $row['level']),
				"order" => (int) $row['order'],
				"active" => (int) $row['active'],
				"completed" => (int) $row['completed'],
				'tie' => empty($row['winner_id']), // ничья
				"player1" => [
					"id" => (int) ($row['player_one_id'] ?? 0),
					"score" => (is_null($row['score_one']) ? null : (int) $row['score_one']),
					"winner" =>((int) $row['winner_id'] === (int) $row['player_one_id']),
					"loser" =>((int) $row['loser_id'] === (int) $row['player_one_id']),
					"name" => (string) ($player1['nick'] ?? ''),
					"color" => (empty($player1) ? '' : (string) $this->classMap[$player1['class_id']]['avatar']),
				],
				"player2" => [
					"id" => (int) ($row['player_two_id'] ?? 0),
					"score" => (is_null($row['score_two']) ? null : (int) $row['score_two']),
					"winner" =>((int) $row['winner_id'] === (int) $row['player_two_id']),
					"loser" =>((int) $row['loser_id'] === (int) $row['player_two_id']),
					"name" => (string) ($player2['nick'] ?? ''),
					"color" => (empty($player2) ? '' : (string) $this->classMap[$player2['class_id']]['avatar']),
				],
			];
		}
		
		return $result;
	}
	
	private function getPartisipants(): array
	{
		$result = [];
		
		foreach ($this->players as $row) {
			$result[] = [
				'name' => (string) $row['nick'],
				'class' => $this->classMap[$row['class_id']]['name'],
				'color' => $this->classMap[$row['class_id']]['avatar'],
				'avatar' => $this->raceMap[$row['race_id']]['avatar'],
			];
		}
				
		return $result;
	}
	
	private function loadStandingRounds(): void
	{
		foreach ($this->brackets as &$bracket)
		{
			if ($bracket['type'] !== Bracket::TYPE_TABLE) {
				continue;
			}
			
			$rows = (new \common\services\BracketTableService())->getBracketTableRows($bracket['id']);
			$headers = [];
			
			foreach ($rows as &$row) {
				$row = [
					'nick' => $row['nick'],
					'class' => $row['class'],
					'color' => $this->classMap[$row['class_id']]['avatar'],
					'avatar' => $this->raceMap[$row['race_id']]['avatar'],
					'race' => $row['race'],
					'faction' => $row['faction'],
					'faction_avatar' => IMG_ROOT . '/' . trim($this->factionMap[$row['faction_id']]['avatar']),
					'world' => $row['world'],
					'team' => $row['team'],
					'columns' => array_map(
						function ($col) {
							return [
								'title' => $col['title'],
								'value' => $col['value'],
							];
						},
						array_values($row['columns'] ?? [])
					),
				];
						
				if (empty($headers)) {
					$headers = array_column($row['columns'], 'title');
				}
			}
			
			unset($bracket['rounds']);
			$bracket['headers'] = $headers;
			$bracket['rows'] = array_values($rows);
		}
	}
	
	private function debug($value): void
	{
		echo '<pre>';
		print_r($value);
		echo '<pre>';
	}
	
}
