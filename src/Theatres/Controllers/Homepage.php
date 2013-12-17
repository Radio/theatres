<?php

namespace Theatres\Controllers;

use Silex\Application;
use RedBean_Facade as R;

class Homepage
{
    public function index(Application $app)
    {
        $plays = R::findAll('play', 'order by `date`');

        $cal = $this->generateCalendar($plays);
        $this->fillCalWithPlays($cal, $plays);

        $context = array(
            'base' => '/theatres/web',
            'plays' => $plays,
            'cal' => $cal
        );

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        return $twig->render('homepage.html', $context);
    }

    private function generateCalendar()
    {
        $cal = array();

        $today = (int) date('j');
        $month = (int) date('n');
        $year  = (int) date('Y');
        $daysInMonth = (int) date('t');

        $week = 1;
        $firstDay = new \DateTime("$year-$month-1");
        $dayOfWeek = (int) $firstDay->format('N');


        // 1. Pad $cal with days from previous month
        /*
        $previousMonthDate = clone $firstDay;
        $previousMonthDate->modify('-1 month');
        $previousMonth = $previousMonthDate->format('n');
        $previousYear = $previousMonthDate->format('Y');
        */
        for ($dayOfWeekPrevious = 1; $dayOfWeekPrevious <= $dayOfWeek; $dayOfWeekPrevious++) {
            $prevDay = clone $firstDay;
            $diff = $dayOfWeek - $dayOfWeekPrevious;
            $cal[$week - 1][$dayOfWeekPrevious - 1] = array(
                'date' => $prevDay->modify("-$diff day"),
                'month' => 'previous',
                'today' => false,
            );
        }

        // 2. Fill $cal with days from current month
        for ($day = 1; $day <= $daysInMonth; $day++, $dayOfWeek++) {
            if ($dayOfWeek > 7) {
                $dayOfWeek = 1;
                $week++;
            }
            $cal[$week - 1][$dayOfWeek - 1] = array(
                'date' => new \DateTime("$year-$month-$day"),
                'month' => 'current',
                'today' => ($today == $day)
            );
        }

        // 3. Pad $cal with days from next month
        $nextMonthDate = clone $firstDay;
        $nextMonthDate->modify('+1 month');
        $nextMonth = $nextMonthDate->format('n');
        $nextYear = $nextMonthDate->format('Y');
        for ($dayOfWeekNext = $dayOfWeek, $nextDay = 1;
             $dayOfWeekNext <= 7;
             $dayOfWeekNext++, $nextDay++) {

            $cal[$week - 1][$dayOfWeekNext - 1] = array(
                'date' => new \DateTime("$nextYear-$nextMonth-$nextDay"),
                'month' => 'next',
                'today' => false,
            );
        }

        return $cal;
    }

    /**
     * @param array $cal
     * @param \Theatres\Models\Play[] $plays
     * @return array
     */
    private function fillCalWithPlays(&$cal, array $plays)
    {
        foreach ($cal as &$week) {
            foreach ($week as &$day) {
                $day['plays'] = $this->getPlaysAtDate($day['date'], $plays);
            }
        }
    }

    /**
     * @param \DateTime $date
     * @param \Theatres\Models\Play[] $plays
     * @return \Theatres\Models\Play[]
     */
    private function getPlaysAtDate(\DateTime $date, array $plays)
    {
        $playsAtDate = array();
        $date->setTime(0, 0);
        foreach ($plays as $play) {
            if ($play->getDate() == $date) {
                $playsAtDate[] = $play;
            }
        }

        return $playsAtDate;
    }
}