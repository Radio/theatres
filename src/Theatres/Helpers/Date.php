<?php

namespace Theatres\Helpers;

class Date
{
    private static $dayOfWeekNames = array(
        1 => 'понедельник',
             'вторник',
             'среда',
             'четверг',
             'пятница',
             'суббота',
             'воскресение',
    );

    public static function getDayOfWeekName(\DateTime $date)
    {
        return self::$dayOfWeekNames[$date->format('N')];
    }
}