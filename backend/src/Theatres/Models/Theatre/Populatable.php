<?php

namespace Theatres\Models;

trait Theatre_Populatable
{
    /**
     * @var bool Flag which indicates that we should populate the theatre.
     */
    protected $populateTheatreFlag = false;

    /**
     * @var array List of fields to populate.
     */
    protected $theatrePopulatingFields = [
        'title', 'abbr', 'link', 'key', 'house_slug'
    ];

    /**
     * Set populate flag.
     *
     * @param boolean $populateTheatreFlag Yes or No.
     */
    public function setPopulateTheatreFlag($populateTheatreFlag)
    {
        $this->populateTheatreFlag = $populateTheatreFlag;
    }

    /**
     * Adjust sql statements so that theatre fields are added.
     *
     * @param $selectStatement
     * @param $fromStatement
     * @param $mainTable
     */
    protected function populateTheatre(&$selectStatement, &$fromStatement, $mainTable)
    {
        foreach ($this->theatrePopulatingFields as $theatreField) {
            $selectStatement .= ', theatre.`' . $theatreField . '` as theatre_' . $theatreField;
        }
        $fromStatement .= ' LEFT JOIN theatre on ' . $mainTable . '.theatre_id = theatre.`id`';
    }

    protected function normalizePopulatedTheatreBooleanFields($itemData)
    {
        foreach (Theatre::$booleanFields as $theatreField) {
            if (array_key_exists('theatre_' . $theatreField, $itemData)) {
                $itemData['theatre_' . $theatreField] = (bool) $itemData['theatre_' . $theatreField];
            }
        }

        return $itemData;
    }
}