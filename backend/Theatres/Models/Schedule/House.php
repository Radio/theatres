<?php

namespace Theatres\Models;

use RedBean_Facade as R;

/**
 * Theatre Schedule
 * @package Theatres\Models
 */
class Schedule_House extends Schedule
{
    protected function clearSchedule($month, $year)
    {
        R::exec(
            'delete from `show` where
              theatre in (select `key` from theatre where house_slug is not null and house_slug != "")
              and month (`date`) = ?
              and year(`date`) = ?',
            array($month, $year));
    }
}