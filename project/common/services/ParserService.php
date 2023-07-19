<?php

namespace common\services;

use Yii;
use common\models\PlayerClass;
use common\models\PlayerFaction;
use common\models\PlayerRace;
use common\models\PlayerWorld;

class ParserService
{
    const TARGET_WOW_COM = 'WorldofwarcraftCom';

    /**
     * @var array
     */
    private $knownTargets = ['WorldofwarcraftCom'];

    /**
     * @var array
     */
    private $playerInitData = [
        'nick' => null,
        'classId' => null,
        'raceId' => null,
        'factionId' => null,
        'worldId' => null,
    ];

    /**
     * @param string $url
     * @param string $target
     * @return bool
     * @throws \Throwable
     */
    public function parsePlayer(string $url, string $target)
    {
        if (!in_array($target, $this->knownTargets)) {
            throw new \Exception('Unknown target for player parser.');
        }

        $langFound = preg_match('/\/([a-z]{2}\-[a-z]{2})\//i', $url, $match);
        if ($langFound && !in_array($match[1], ['en-gb', 'en-us'])) {
            $url = preg_replace('/\/[a-z]{2}\-[a-z]{2}\//i', '/en-us/', $url);
        }

        $data = $this->getUrlContent($url);
        if ($data === false) return false;

        $parseMethod = 'parse' . $target;

        $playerData = $this->$parseMethod($data);
        if ($playerData['avatar']) {
            $this->downloadAvatar($playerData['avatar'], $playerData['nick']);
        }
        return $playerData;
    }


    public function downloadAvatar(string $url, string $nick)
    {
        $this->downloadImage(
            $url,
            Yii::getAlias('@app') . '/web/storage/images/user/',
            md5($nick)
        );
    }

    /**
     * @param string $data
     * @return array|bool
     */
    private function parseWorldofwarcraftCom(string $data)
    {
        if (!preg_match('/var characterProfileInitialState = \{(.+?)\};/i', $data, $matches)) {
            return false;
        }

        //file_put_contents(Yii::getAlias('@app/parser-data.txt'), $matches[1]);

        $player = $this->playerInitData;
        $character = json_decode("{{$matches[1]}}", true);

        //file_put_contents(Yii::getAlias('@app/parser-array.txt'), var_export($character, true));

        // nick
        $player['nick'] = $character['character']['name'] ?? '';

        // gender
        $gender = $character['character']['gender']['name'] ?? '';
        $player['gender'] = trim($gender) ? trim($gender) : null;
        $player['genderId'] = strtolower($player['gender']) == 'female'
            ? PlayerRace::GENDER_FEMALE : PlayerRace::GENDER_MALE;

        // class
        $class = $character['character']['class']['name'] ?? '';
        if (trim($class) && $classes = PlayerClass::find()->where(['like', 'name', trim($class)])->all()) {
            $classId = $classes[0]->id;
        }
        $player['classId'] = $classId ?? null;

        // race
        $race = $character['character']['race']['name'] ?? '';
        if (trim($race) && $races = PlayerRace::find()
            //->where(['like', 'name', trim($race)])
            ->where('name like :name', [':name' => trim($race)])
            ->andWhere(['gender' => $player['genderId']])
            ->all()
        ) {
            $raceId = $races[0]->id;
        }
        $player['raceId'] = $raceId ?? null;

        // faction
        $faction = $character['character']['faction']['name'] ?? '';
        if (trim($faction) && $factions = PlayerFaction::find()->where(['like', 'name', trim($faction)])->all()) {
            $factionId = $factions[0]->id;
        }
        $player['factionId'] = $factionId ?? null;

        // $world = $character['character']['pve']['id'] ?? '';
        $world = $character['character']['realm']['name'] ?? '';
        if (trim($world)) {
            if ($worlds = PlayerWorld::find()->where(['like', 'name', trim($world)])->all()) {
                $worldId = $worlds[0]->id;
                $worldName = $worlds[0]->name;
            } else {
                $worldModel = new PlayerWorld;
                $worldModel->name = trim($world);
                if ($worldModel->save()) {
                    $worldId = $worldModel->id;
                    $worldName = $worldModel->name;
                }
            }
        }
        $player['worldId'] = $worldId ?? null;
        $player['world'] = $worldName ?? null;

        // avatar
        $avatar = $character['character']['avatar']['url'] ?? '';
        $player['avatar'] = trim($avatar) ? trim($avatar) : null;

        return $player;
    }

    /**
     * @param string $url
     * @return bool|string
     */
    private function getUrlContent(string $url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    private function downloadImage($url, $folder, $filename = false)
    {
        if ($filename === false) {
            $localPath = $folder . basename($url);
        } else {
            $localPath = $folder . $filename . '.' . pathinfo( basename($url), PATHINFO_EXTENSION );
        }

        if (!file_exists($folder)) mkdir($folder, 0777);

        $ch = curl_init($url);
        $fp = fopen($localPath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        return $localPath;
    }
}