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

    private static $monthNames = array(
        1 => 'январь',
             'февраль',
             'март',
             'апрель',
             'май',
             'июнь',
             'июль',
             'август',
             'сентябрь',
             'октябрь',
             'ноябрь',
             'декабрь',
    );

    public static function getDayOfWeekName(\DateTime $date)
    {
        return self::$dayOfWeekNames[$date->format('N')];
    }

    public static function getMonthName($monthNumber)
    {
        return isset(self::$monthNames[$monthNumber]) ? self::$monthNames[$monthNumber] : '';
    }
}