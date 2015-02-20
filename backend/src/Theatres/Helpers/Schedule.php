<?php

namespace Theatres\Helpers;

/**
 * Schedule Helper.
 *
 * @package Theatres\Helpers
 */
class Schedule
{
    /**
     * Sort plays by date.
     *
     * @param array $schedule Schedule data.
     */
    public static function sortByDate(&$schedule)
    {
        usort($schedule, function($a, $b) {
            if ($a['date'] == $b['date']) {
                return 0;
            }
            return ($a['date'] < $b['date']) ? -1 : 1;
        });
    }

    /**
     * Group plays by month.
     *
     * @param array $schedule Schedule data.
     * @return array
     */
    public static function groupByMonth($schedule)
    {
        $grouped = [];
        foreach ($schedule as $show)
        {
            $month = $show['date']->format('n');
            if (!isset($grouped[$month])) {
                $grouped[$month] = [];
            }
            $grouped[$month][] = $show;
        }

        return $grouped;
    }
}