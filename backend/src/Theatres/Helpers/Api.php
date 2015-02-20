<?php

namespace Theatres\Helpers;

/**
 * Help to deal with API params.
 *
 * @package Theatres\Helpers
 */
class Api
{
    /** @const string Sql date format. */
    const SQL_DATE_FORMAT = 'Y-m-d';

    /** @const string Sql time format. */
    const SQL_TIME_FORMAT = 'H:i:s';

    /**
     * Check if the value is allowed.
     *
     * @param string|int|float $value Value.
     * @param array $allowedValues List of allowed values.
     * @return bool
     */
    public static function isAllowed($value, array $allowedValues)
    {
        return in_array($value, $allowedValues);
    }

    /**
     * Convert yes/no values to boolean (or null).
     *
     * @param string|null $value Value.
     * @return bool|null
     */
    public static function toBool($value)
    {
        if ($value === 'yes') {
            return true;
        } elseif ($value === 'no') {
            return false;
        } else {
            return null;
        }
    }

    /**
     * Convert date string to DateTime object.
     *
     * @param string $value Date string.
     * @return \DateTime|null
     */
    public static function toDate($value)
    {
        $date = null;
        if ($value) {
            try {
                $date = new \DateTime($value);
            } catch (\Exception $e) {
            }
        }

        return $date;
    }

    /**
     * Convert date string to sql date format.
     *
     * @param string $value Date string.
     * @return string|null
     */
    public static function toSqlDate($value)
    {
        $date = self::toDate($value);

        if ($date) {
            return $date->format(self::SQL_DATE_FORMAT);
        }

        return null;
    }

    /**
     * Convert time string to sql time format.
     *
     * @param string $value Time string.
     * @return string|null
     */
    public static function toSqlTime($value)
    {
        $date = self::toDate($value);

        if ($date) {
            return $date->format(self::SQL_TIME_FORMAT);
        }

        return null;
    }
}