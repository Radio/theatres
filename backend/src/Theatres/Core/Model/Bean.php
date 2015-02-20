<?php

namespace Theatres\Core;

abstract class Model_Bean extends \RedBean_SimpleModel
{
    /**
     * @var array List of boolean fields.
     */
    protected static $booleanFields = [];

    /**
     * Convert bean to array.
     */
    public function toArray()
    {
        $exportedData = $this->bean->export();
        $exportedData = static::normalizeBooleanFields($exportedData, static::$booleanFields);

        return $exportedData;
    }

    /**
     * Convert '1'/'0'/null values from mysql to boolean.
     *
     * @param array $itemData      Item Data.
     * @param array $booleanFields List of boolean fields.
     * @return array
     */
    public static function normalizeBooleanFields($itemData, $booleanFields)
    {
        foreach ($booleanFields as $booleanField) {
            if (array_key_exists($booleanField, $itemData)) {
                $itemData[$booleanField] = (bool) $itemData[$booleanField];
            }
        }

        return $itemData;
    }
}