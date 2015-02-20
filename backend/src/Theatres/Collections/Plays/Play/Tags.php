<?php

namespace Theatres\Collections;

use Theatres\Core\Exceptions\Collection_RequiredParametersMissing;
use RedBean_Facade as R;

class Plays_Play_Tags extends Tags
{
    /** @var int */
    protected $playId;

    /**
     * Check if play ID is set.
     *
     * @throws \Theatres\Core\Exceptions\Collection_RequiredParametersMissing
     */
    private function beforeQuerying()
    {
        if (!$this->playId) {
            throw new Collection_RequiredParametersMissing('Play ID is required for this collection.');
        }
        $this->addConditions('`play_tag`.`play_id` = ?', array($this->playId));
    }

    /**
     * Check if play ID is set before loading.
     *
     * @throws \Theatres\Core\Exceptions\Collection_RequiredParametersMissing
     */
    protected function beforeLoad()
    {
        $this->beforeQuerying();
    }

    /**
     * Check if play ID is set before deletion.
     *
     * @throws \Theatres\Core\Exceptions\Collection_RequiredParametersMissing
     */
    protected function beforeDelete()
    {
        $this->beforeQuerying();
    }

    /**
     * Join `play_tag` table and filter by play ID.
     *
     * @param string $sql Part of SQL after WHERE.
     * @return \RedBean_OODBBean[]
     */
    protected function runLoadSql($sql)
    {
        $query = 'SELECT `tag`.* FROM `play_tag` JOIN `tag` on `play_tag`.tag_id = `tag`.`id` ';
        if ($this->conditions) {
            $query .= 'where ' . $sql;
        } else {
            $query .= $sql;
        }
        $rows = R::getAll($query, $this->bindings);
        $beans = R::convertToBeans($this->beanType, $rows);

        return $beans;
    }

    /**
     * Join `play_tag` table and filter by play ID.
     *
     * @param $sql
     * @return \RedBean_OODBBean[]
     */
    protected function runDeleteSql($sql)
    {
        $query = 'DELETE from `play_tag` ';
        if ($this->conditions) {
            $query .= 'where ' . $sql;
        } else {
            $query .= $sql;
        }
        return R::exec($query, $this->bindings);
    }

    /**
     * @param mixed $playId
     */
    public function setPlayId($playId)
    {
        $this->playId = $playId;
    }

    /**
     * @return mixed
     */
    public function getPlayId()
    {
        return $this->playId;
    }
}