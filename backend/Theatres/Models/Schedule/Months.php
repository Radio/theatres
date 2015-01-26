<?php

namespace Theatres\Models;

use Theatres\Helpers;

/**
 * Theatre Schedule
 * @package Theatres\Models
 */
class Schedule_Months extends Schedule
{
    public function saveSchedule($showsData, $_month, $year)
    {
        $showsDataGrouped = Helpers\Schedule::groupByMonth($showsData);
        foreach ($showsDataGrouped as $month => $showsDataForMonth) {
            parent::saveSchedule($showsDataForMonth, $month, $year);
        }
    }
}