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
        'nominative'=> array(
            1 => 'январь', 'февраль', 'март', 'апрель',
                 'май', 'июнь', 'июль', 'август',
                 'сентябрь', 'октябрь', 'ноябрь', 'декабрь',
        ),
        'genitive' => array(
            1 => 'января', 'февраля', 'марта', 'апреля',
                'мая', 'июня', 'июля', 'августа',
                'сентября', 'октября', 'ноября', 'декабря'
        )
    );

    public static function getDayOfWeekName(\DateTime $date)
    {
        return self::$dayOfWeekNames[$date->format('N')];
    }

    public static function getMonthName($monthNumber)
    {
        return isset(self::$monthNames['nominative'][$monthNumber])
            ? self::$monthNames['nominative'][$monthNumber] : '';
    }

    public static function mapMonthTitle($title, $case = 'nominative')
    {
        foreach (self::$monthNames[$case] as $monthNumber => $monthName) {
            if (mb_strtolower($title, 'utf-8') == $monthName) {
                return $monthNumber;
            }
        }

        return null;
    }

    public static function mapMonthShortTitle($title)
    {
        foreach (self::$monthNames['nominative'] as $monthNumber => $monthName) {
            if (strpos($monthName, mb_strtolower($title, 'utf-8')) === 0) {
                return $monthNumber;
            }
        }

        return null;
    }
}