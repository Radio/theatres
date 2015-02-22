<?php

namespace Theatres\Models;

trait Play_Populatable
{
    /**
     * @var bool Flag which indicates that we should populate the play.
     */
    protected $populatePlayFlag = false;

    /**
     * @var array List of fields to populate.
     */
    protected $playPopulatingFields = [
        'key', 'theatre_id', 'title', 'scene_id', 'link', 'is_premiere', 'is_for_children', 'is_musical'
    ];

    /**
     * Set populate flag.
     *
     * @param boolean $populatePlayFlag Yes or No.
     */
    public function setPopulatePlayFlag($populatePlayFlag)
    {
        $this->populatePlayFlag = $populatePlayFlag;
    }

    /**
     * Adjust sql statements so that play fields are added.
     *
     * @param $selectStatement
     * @param $fromStatement
     * @param $mainTable
     */
    protected function populatePlay(&$selectStatement, &$fromStatement, $mainTable)
    {
        foreach ($this->playPopulatingFields as $playField) {
            $selectStatement .= ', play.`' . $playField . '` as play_' . $playField;
        }
        $fromStatement .= ' LEFT JOIN play on ' . $mainTable . '.play_id = play.`id`';
    }

    protected function normalizePopulatedPlayBooleanFields($itemData)
    {
        foreach (Play::$booleanFields as $playField) {
            if (array_key_exists('play_' . $playField, $itemData)) {
                $itemData['play_' . $playField] = (bool) $itemData['play_' . $playField];
            }
        }

        return $itemData;
    }
}