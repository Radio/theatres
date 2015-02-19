<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;
use Theatres\Models\Play_Populatable;
use Theatres\Models\Scene_Populatable;
use Theatres\Models\Theatre_Populatable;

class Shows extends Beans
{
    use Play_Populatable;
    use Theatre_Populatable;
    use Scene_Populatable;

    public function __construct()
    {
        $this->beanType = 'show';
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
        if ($this->populatePlayFlag) {
            $this->populatePlay($selectStatement, $fromStatement, $mainTable);
        }
        if ($this->populateSceneFlag) {
            $this->populateScene($selectStatement, $fromStatement, $mainTable);
        }
        if ($this->populateTheatreFlag) {
            $this->populateTheatre($selectStatement, $fromStatement, $mainTable);
        }
    }

    /**
     * Normalize boolean fields.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        return array_map(function($show) {
            $show = $this->normalizePopulatedPlayBooleanFields($show);
            $show = $this->normalizePopulatedTheatreBooleanFields($show);

            return $show;
        }, $data);
    }
}