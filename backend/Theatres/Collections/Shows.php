<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;
use RedBean_Facade as R;

class Shows extends Beans
{
    protected $populatePlay;

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
        if ($this->populatePlay) {
            $playFields = array(
                'id', 'key', 'theatre', 'title', 'scene', 'link'
            );
            $query = 'SELECT `show`.*';
            foreach ($playFields as $playField) {
                $query .= ', play.`' . $playField . '` as play_' . $playField;
            }
            $query .= ' FROM `show` LEFT JOIN play on show.play = play.`key` ';
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
     * @param mixed $populatePlays
     */
    public function setPopulatePlay($populatePlays)
    {
        $this->populatePlay = $populatePlays;
    }
}