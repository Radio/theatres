<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;
use RedBean_Facade as R;

class Shows extends Beans
{
    protected $populatePlayFlag = false;
    protected $populateTheatreFlag = false;
    protected $populateSceneFlag = false;

    public function __construct()
    {
        $this->beanType = 'show';
    }

    /**
     * @param $sql
     * @return array
     */
    protected function runLoadSql($sql)
    {
        if ($this->populatePlayFlag || $this->populateSceneFlag || $this->populateTheatreFlag) {

            $selectStatement = 'SELECT `show`.*';
            $fromStatement = ' FROM `show`';

            if ($this->populatePlayFlag) {
                $this->populatePlay($selectStatement, $fromStatement);
            }
            if ($this->populateSceneFlag) {
                $this->populateScene($selectStatement, $fromStatement);
            }
            if ($this->populateTheatreFlag) {
                $this->populateTheatre($selectStatement, $fromStatement);
            }

            $query = $selectStatement . ' ' . $fromStatement;
            if ($this->conditions) {
                $query .= 'where ' . $sql;
            } else {
                $query .= $sql;
            }
            $rows = R::getAll($query, $this->bindings);
            return R::convertToBeans($this->beanType, $rows);
        } else {
            return R::findAll($this->beanType, $sql, $this->bindings);
        }
    }

    /**
     * @param boolean $populatePlayFlag
     */
    public function setPopulatePlayFlag($populatePlayFlag)
    {
        $this->populatePlayFlag = $populatePlayFlag;
    }

    /**
     * @param boolean $populateSceneFlag
     */
    public function setPopulateSceneFlag($populateSceneFlag)
    {
        $this->populateSceneFlag = $populateSceneFlag;
    }

    /**
     * @param boolean $populateTheatreFlag
     */
    public function setPopulateTheatreFlag($populateTheatreFlag)
    {
        $this->populateTheatreFlag = $populateTheatreFlag;
    }

    /**
     * @param $selectStatement
     * @param $fromStatement
     */
    protected function populatePlay(&$selectStatement, &$fromStatement)
    {
        $playFields = array(
            'key', 'theatre_id', 'title', 'scene_id', 'link', 'is_premiere', 'is_for_children', 'is_musical'
        );
        foreach ($playFields as $playField) {
            $selectStatement .= ', play.`' . $playField . '` as play_' . $playField;
        }
        $fromStatement .= ' LEFT JOIN play on show.play_id = play.`id`';
    }

    /**
     * @param $selectStatement
     * @param $fromStatement
     */
    protected function populateTheatre(&$selectStatement, &$fromStatement)
    {
        $fields = array(
            'title', 'abbr', 'link', 'has_fetcher', 'key', 'house_slug'
        );
        foreach ($fields as $field) {
            $selectStatement .= ', theatre.`' . $field . '` as theatre_' . $field;
        }
        $fromStatement .= ' LEFT JOIN theatre on show.theatre_id = theatre.`id` ';
    }

    /**
     * @param $selectStatement
     * @param $fromStatement
     */
    protected function populateScene(&$selectStatement, &$fromStatement)
    {
        $fields = array(
            'title', 'key'
        );
        foreach ($fields as $field) {
            $selectStatement .= ', scene.`' . $field . '` as scene_' . $field;
        }
        $fromStatement .= ' LEFT JOIN scene on show.scene_id = scene.`id` ';
    }

    public function toArray()
    {
        $playFields = ['is_premiere', 'is_for_children', 'is_musical'];
        $theatreFields = ['has_fetcher'];
        $data = parent::toArray();
        return array_map(function($show) use ($playFields, $theatreFields) {
            foreach ($playFields as $playField) {
                if (array_key_exists('play_' . $playField, $show)) {
                    $show['play_' . $playField] = (bool) $show['play_' . $playField];
                }
            }
            foreach ($theatreFields as $theatreField) {
                if (array_key_exists('theatre_' . $theatreField, $show)) {
                    $show['theatre_' . $theatreField] = (bool) $show['theatre_' . $theatreField];
                }
            }

            return $show;
        }, $data);
    }
}