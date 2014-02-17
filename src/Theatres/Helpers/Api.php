<?php

namespace Theatres\Helpers;

class Api
{
    /**
     * Check if the value is allowed
     *
     * @param string|int|float $value
     * @param array $allowedValues
     * @return bool
     */
    public static function isAllowed($value, array $allowedValues)
    {
        return in_array($value, $allowedValues);
    }

    /**
     * Convert yes/no values to boolean (or null)
     *
     * @param string|null $value
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
}