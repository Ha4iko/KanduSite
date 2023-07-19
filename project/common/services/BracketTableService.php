<?php

namespace common\services;

use Yii;

class BracketTableService
{

    /**
     * conditions aliases:
     * player p
     * tournament_to_player ttp
     * ...
     * example: $conditions = ['p.nick like "%Slayer%"']
     *
     * @param $bracketId
     * @param array $conditions
     * @return array
     * @throws \Exception
     */
    public function getBracketTableRows($bracketId, array $conditions = [])
    {
        $query = $this->getBracketTableRowsRawSql($bracketId, $conditions);
        $rows = Yii::$app->db->createCommand($query)->queryAll();

        $groupedByParticipant = [];
        foreach ($rows as $row) {
            $groupedByParticipant[$row['row_id']]['id'] = (integer) $row['row_id'];
            $groupedByParticipant[$row['row_id']]['participant_id'] = (integer) $row['participant_id'];
            $groupedByParticipant[$row['row_id']]['player_id'] = (integer) $row['player_id'];
            $groupedByParticipant[$row['row_id']]['nick'] = $row['nick'];
            $groupedByParticipant[$row['row_id']]['class_id'] = $row['class_id'];
            $groupedByParticipant[$row['row_id']]['race_id'] = $row['race_id'];
            $groupedByParticipant[$row['row_id']]['faction_id'] = $row['faction_id'];
            $groupedByParticipant[$row['row_id']]['world_id'] = $row['world_id'];
            $groupedByParticipant[$row['row_id']]['team_id'] = $row['team_id'];
            $groupedByParticipant[$row['row_id']]['class'] = $row['class_name'];
            $groupedByParticipant[$row['row_id']]['race'] = $row['race_name'];
            $groupedByParticipant[$row['row_id']]['faction'] = $row['faction_name'];
            $groupedByParticipant[$row['row_id']]['world'] = $row['world_name'];
            $groupedByParticipant[$row['row_id']]['team'] = $row['team_name'];
            if (!$row['column_active']) continue;
            $groupedByParticipant[$row['row_id']]['columns'][$row['column_id']] = [
                'id' => (integer) $row['column_id'],
                'title' => $row['column_title'],
                'value' => $row['value'],
                'cell_id' => $row['cell_id'],
                'top' => (integer) $row['top']
                //'active' => $row['column_active'],
            ];
        }

        return $groupedByParticipant;
    }

    /**
     * conditions aliases:
     * player p
     * tournament_to_player ttp
     * ...
     * example: $conditions = ['p.nick like "%Slayer%"']
     *
     * @param $bracketId
     * @param array $conditions
     * @return string
     * @throws \Exception
     */
    public function getBracketTableRowsRawSql($bracketId, array $conditions = [])
    {
        $bracketId = intval($bracketId);

        if (!$bracketId) throw new \Exception('Bracket id is wrong.');

        $where = '';
        if (count($conditions)) {
            $where = 'where ' . implode(' AND ', $conditions);
        }

        return <<<SQL
            SELECT t.*, c.id as cell_id, c.value, c.top, p.id as player_id, p.nick, 
                   ttp.class_id, ttp.race_id, ttp.faction_id, ttp.world_id, ttp.team_id,
                   pc.name as class_name, pr.name as race_name, pf.name as faction_name, 
                   pw.name as world_name, tm.name as team_name 
            FROM (
                SELECT r.id as row_id, r.tournament_to_player_id as participant_id,
                       c.id as column_id, c.order as column_order, c.title as column_title, c.active as column_active
                FROM bracket_table_column c
                LEFT JOIN bracket_table_row r on r.bracket_id = c.bracket_id

                WHERE c.bracket_id = {$bracketId}
                ORDER BY r.id, c.order, c.id
            ) t

            LEFT JOIN bracket_table_cell c on c.bracket_table_row_id = t.row_id and c.bracket_table_column_id = t.column_id
            LEFT JOIN tournament_to_player ttp on ttp.id = t.participant_id
            LEFT JOIN player p on p.id = ttp.player_id
            LEFT JOIN player_class pc on pc.id = ttp.class_id
            LEFT JOIN player_race pr on pr.id = ttp.race_id
            LEFT JOIN player_faction pf on pf.id = ttp.faction_id
            LEFT JOIN player_world pw on pw.id = ttp.world_id
            LEFT JOIN team tm on tm.id = ttp.team_id

            {$where}

            ORDER BY t.row_id, t.column_order, t.column_id
SQL;

    }

    /**
     * conditions aliases:
     * team tm
     * tournament_to_team ttt
     * ...
     * example: $conditions = ['tm.name like "%WinTeam%"']
     *
     * @param $bracketId
     * @param array $conditions
     * @return array
     * @throws \Exception
     */
    public function getBracketTableRowsTeam($bracketId, array $conditions = [])
    {
        $query = $this->getBracketTableRowsTeamRawSql($bracketId, $conditions);
        $rows = Yii::$app->db->createCommand($query)->queryAll();

        $groupedByParticipant = [];
        foreach ($rows as $row) {
            $groupedByParticipant[$row['row_id']]['id'] = (integer) $row['row_id'];
            $groupedByParticipant[$row['row_id']]['participant_id'] = (integer) $row['participant_id'];
            $groupedByParticipant[$row['row_id']]['team_id'] = (integer) $row['team_id'];
            $groupedByParticipant[$row['row_id']]['team_name'] = $row['team_name'];
            if (!$row['column_active']) continue;
            $groupedByParticipant[$row['row_id']]['columns'][$row['column_id']] = [
                'id' => (integer) $row['column_id'],
                'title' => $row['column_title'],
                'value' => $row['value'],
                'cell_id' => $row['cell_id'],
                'top' => (integer) $row['top'],
                //'active' => $row['column_active'],
            ];
        }

        return $groupedByParticipant;
    }

    /**
     * conditions aliases:
     * team tm
     * tournament_to_team ttt
     * ...
     * example: $conditions = ['tm.name like "%WinTeam%"']
     *
     * @param $bracketId
     * @param array $conditions
     * @return string
     * @throws \Exception
     */
    public function getBracketTableRowsTeamRawSql($bracketId, array $conditions = [])
    {
        $bracketId = intval($bracketId);

        if (!$bracketId) throw new \Exception('Bracket id is wrong.');

        $where = '';
        if (count($conditions)) {
            $where = 'where ' . implode(' AND ', $conditions);
        }

        return <<<SQL
            SELECT t.*, c.id as cell_id, c.value, c.top, tm.id as team_id, tm.name as team_name 
            FROM (
                SELECT r.id as row_id, r.tournament_to_team_id as participant_id,
                       c.id as column_id, c.order as column_order, c.title as column_title, c.active as column_active
                FROM bracket_table_column c
                LEFT JOIN bracket_table_row_team r on r.bracket_id = c.bracket_id

                WHERE c.bracket_id = {$bracketId}
                ORDER BY r.id, c.order, c.id
            ) t

            LEFT JOIN bracket_table_cell_team c on c.bracket_table_row_id = t.row_id and c.bracket_table_column_id = t.column_id
            LEFT JOIN tournament_to_team ttt on ttt.id = t.participant_id
            LEFT JOIN team tm on tm.id = ttt.team_id

            {$where}

            ORDER BY t.row_id, t.column_order, t.column_id
SQL;

    }
}