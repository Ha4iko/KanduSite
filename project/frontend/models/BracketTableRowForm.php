<?php

namespace frontend\models;

use common\models\BracketTableCell;
use common\services\BracketTableService;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * @property string $bracketId
 */
class BracketTableRowForm extends Model
{
    /**
     * @var int
     */
    public $bracketId;

    /**
     * @var array
     */
    public $cells = [];

    /**
     * @var BracketTableService
     */
    private $bracketService;

    /**
     * @var Bracket
     */
    public $bracket;


    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        parent::init();
        $this->bracketService = Yii::$container->get(BracketTableService::class);
        $this->bracketId = intval($this->bracketId);
        if (!$this->bracketId) throw new InvalidConfigException('Wrong bracket id.');

        $this->bracket = Bracket::findOne($this->bracketId);
        if (!is_object($this->bracket)) throw new InvalidConfigException('Bracket not found.');
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTableRows()
    {
        $tournamentId = $this->bracket->tournament_id;

        $tableRows = $this->bracketService->getBracketTableRows($this->bracketId);

        $rowNumber = 1;
        foreach ($tableRows as &$tableRow) {
            $tableRow['row_number'] = $rowNumber++;

            $playerAvatar = '';
            $playerLink = '';
            $factionAvatar = '';
            $classCollor = '';
            if ($player = Player::findOne($tableRow['player_id'])) {
                $playerAvatar = $player->getAvatar($tournamentId);
                $playerLink = $player->external_link ?: '';
                $classCollor = $player->getClassColor($tournamentId);
                $factionAvatar = $player->getFactionAvatar($tournamentId);
            }

            $tableRow['player_avatar'] = $playerAvatar;
            $tableRow['collor_class'] = $classCollor;
            $tableRow['player_link'] = $playerLink;
            $tableRow['faction_avatar'] = $factionAvatar;
        }

        return $tableRows;
    }

    /**
     * @param $data
     * @param null $formName
     * @return bool
     */
    public function loadForm($data, $formName = null)
    {
        $data = $this->prepareData($data);

        $this->cells = [];

        if (Yii::$app->request->isPost && isset($data['BracketTableCell'])) {

            $this->cells = $data['BracketTableCell'];

            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data)
    {
        $classNameInData = 'BracketTableCell';
        $dataModels = $data[$classNameInData] ?? [];
        foreach ($dataModels as $k => &$dataModel) {
            if (!isset($dataModel['bracket_table_row_id']) || !trim($dataModel['bracket_table_row_id'])) {
                unset($dataModels[$k]);
            } elseif (!isset($dataModel['bracket_table_column_id']) || !trim($dataModel['bracket_table_column_id'])) {
                unset($dataModels[$k]);
            } else {
                $dataModel['value'] = trim($dataModel['value'] ?? '');
            }
        }
        $data[$classNameInData] = $dataModels;

        return $data;
    }

    /**
     * @return bool
     */
    public function saveForm()
    {
        if ($this->cells) {
            foreach ($this->cells as $cellData) {
                if ($cellData['id'] && ($cell = BracketTableCell::findOne($cellData['id']))) {
                    if ($cellData['value'] || $cellData['top']) {
                        $cell->value = $cellData['value'];
                        $cell->top = (int)$cellData['top'];
                        $cell->save();
                    } else {
                        $cell->delete();
                    }
                } else {
                    if ($cellData['value'] || $cellData['top']) {
                        $cell = new BracketTableCell();
                        $cell->bracket_table_row_id = $cellData['bracket_table_row_id'];
                        $cell->bracket_table_column_id = $cellData['bracket_table_column_id'];
                        $cell->value = $cellData['value'];
                        $cell->top = (int)$cellData['top'];
                        $cell->save();
                    }
                }
            }
        }

        return true;
    }

}
