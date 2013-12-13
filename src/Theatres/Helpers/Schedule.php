<?php

namespace Theatres\Helpers;

class Schedule
{
    public static function sortByDate(&$schedule)
    {
        usort($schedule, function($a, $b) {
            if ($a['date'] == $b['date']) {
                return 0;
            }
            return ($a['date'] < $b['date']) ? -1 : 1;
        });
    }
}