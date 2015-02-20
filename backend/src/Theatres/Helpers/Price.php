<?php

namespace Theatres\Helpers;

class Price
{
    /**
     * Remove dot after "грн".
     * Fix spacing and change dash to &ndash;.
     *
     * @param string $priceString String containing price.
     * @return string
     */
    public static function normalizePrice($priceString)
    {
        $normalizedPrice = str_replace('грн.', 'грн', trim($priceString));
        $normalizedPrice = preg_replace(
            ['/,([^\s])/', '/\s*[-–−]\s*/isu', '/([^\s])грн/isu'],
            [', $1', '–', '$1 грн'],
            $normalizedPrice
        );

        return $normalizedPrice;
    }
}