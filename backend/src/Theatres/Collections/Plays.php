<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;
use RedBean_Facade as R;

class Plays extends Beans
{
    protected $populateTheatreFlag = false;
    protected $populateSceneFlag = false;

    public function __construct()
    {
        $this->beanType = 'play';
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
     * @param $sql
     * @return array
     */
    protected function runLoadSql($sql)
    {
        if ($this->populateSceneFlag || $this->populateTheatreFlag) {

            $selectStatement = 'SELECT `play`.*';
            $fromStatement = ' FROM `play`';

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
     * @param $selectStatement
     * @param $fromStatement
     */
    protected function populateTheatre(&$selectStatement, &$fromStatement)
    {
        $fields = array(
            'title', 'abbr', 'link', 'key', 'house_slug'
        );
        foreach ($fields as $field) {
            $selectStatement .= ', theatre.`' . $field . '` as theatre_' . $field;
        }
        $fromStatement .= ' LEFT JOIN theatre on play.theatre_id = theatre.`id` ';
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
        $fromStatement .= ' LEFT JOIN scene on play.scene_id = scene.`id` ';
    }

    public function toArray()
    {
        $data = parent::toArray();
        return array_map(function($play) {
            $play['is_premiere'] = (bool) $play['is_premiere'];
            $play['is_for_children'] = (bool) $play['is_for_children'];
            $play['is_musical'] = (bool) $play['is_musical'];
            return $play;
        }, $data);
    }
}