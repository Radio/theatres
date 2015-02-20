<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;
use Theatres\Models\Play;
use Theatres\Models\Scene_Populatable;
use Theatres\Models\Theatre_Populatable;

class Plays extends Beans
{
    use Theatre_Populatable;
    use Scene_Populatable;

    public function __construct()
    {
        $this->beanType = 'play';
        static::$booleanFields = Play::$booleanFields;
    }

    /**
     * Populate result with play, theatre and scene data if needed.
     *
     * @param &string $selectStatement Select statement
     * @param &string $fromStatement From statement
     * @param string $mainTable Main table name or alias.
     */
    protected function adjustLoadSqlStatements(&$selectStatement, &$fromStatement, $mainTable)
    {
        if ($this->populateSceneFlag) {
            $this->populateScene($selectStatement, $fromStatement, $mainTable);
        }
        if ($this->populateTheatreFlag) {
            $this->populateTheatre($selectStatement, $fromStatement, $mainTable);
        }
    }

    public function toArray()
    {
        $data = parent::toArray();
        return array_map(function($play) {
            $play = $this->normalizePopulatedTheatreBooleanFields($play);

            return $play;
        }, $data);
    }
}