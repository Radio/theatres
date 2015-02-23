<?php

namespace Theatres\Models;

use RedBean_Facade as R;

/**
 * Theatre Schedule
 * @package Theatres\Models
 */
class Schedule_House extends Schedule
{
    protected function clearSchedule($month, $year, $showsData = null)
    {
        $firstShow = current($showsData);
        $firstShowDate = $firstShow['date']->format('Y-m-d');

        R::exec(
            'delete from `show` where
              theatre_id in (select `id` from theatre where house_slug is not null and house_slug != "")
              and `date` > ?
              and month (`date`) = ?
              and year(`date`) = ?',
            array($firstShowDate, $month, $year));
    }
}