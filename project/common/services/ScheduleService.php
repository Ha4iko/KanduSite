<?php

namespace common\services;

use frontend\models\Tournament;

class ScheduleService
{
    /**
     * @return array
     * @throws \Exception
     */
    public function getScheduleTournaments()
    {
        $periodDays = 90;
        $periodStartDay = -10;
        $periodEndDay = $periodDays + 1;

        $chartEvents = [];
        $chartEventId = -1;

        $days = [];
        $dayFirst = '';
        $dayLast = '';
        for ($i = $periodStartDay; $i < $periodEndDay; $i++) {
            $dayObject = (new \DateTime($i . ' days'));
            if (!$dayFirst) $dayFirst = $dayObject->format('Y-m-d');
            $dayLast = $dayObject->format('Y-m-d');
            $monthCur = $dayObject->format('F Y');
            $dayCur = $dayObject->format('j');
            $dayName = $dayObject->format('l');
            $days[$monthCur][$dayCur] = substr($dayName, 0, 1);

            $chartEventId++;
            $chartEvents[$dayObject->format('Y-m-d')] = $chartEventId;
        }

        /* @var $scheduleTournaments Tournament[] */
        $scheduleTournaments = Tournament::find()
            ->where([
                'show_on_main_page' => 1,
                'status' => [Tournament::STATUS_IN_PROGRESS, Tournament::STATUS_COMPLETED],
            ])
            ->andWhere('(date_final >= \'' . $dayFirst . '\' AND ' .
                '(date <= \'' . $dayLast . '\' OR date_final is null) )')
            ->orderBy('date, id')
            ->all();

        return [
            'days' => $days,
            'chartEvents' => $chartEvents,
            'scheduleTournaments' => $scheduleTournaments,
            'periodStartDay' => $periodStartDay,
            'periodDays' => $periodDays
        ];
    }
}