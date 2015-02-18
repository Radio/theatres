<?php

namespace Theatres\Core;

use RedBean_Facade as R;
use Theatres\Core\Exceptions\Collection_Beans_UndefinedBeanType as UndefinedBeanType;

abstract class Collection_Beans extends Collection
{
    protected $beanType = null;

    /**
     * @throws Exceptions\Collection_Beans_UndefinedBeanType
     * @return \RedBean_OODBBean[]
     */
    protected function loadData()
    {
        if (!$this->beanType) {
            throw new UndefinedBeanType();
        }

        $sql = $this->buildSql();

        return $this->runLoadSql($sql);
    }

    protected function deleteData()
    {
        if ($this->isLoaded()) {
            R::trashAll($this->items);
        } else {
            $this->deleteViaSql();
        }
    }

    /**
     * @param $sql
     * @return \RedBean_OODBBean[]
     */
    protected function runLoadSql($sql)
    {
        return R::findAll($this->beanType, $sql, $this->bindings);
    }

    /**
     * Delete items via SQL query.
     *
     * @throws Exceptions\Collection_Beans_UndefinedBeanType
     * @return bool
     */
    protected function deleteViaSql()
    {
        if (!$this->beanType) {
            throw new UndefinedBeanType();
        }

        $sql = $this->buildSql();

        return $this->runDeleteSql($sql);
    }

    /**
     * @param $sql
     * @return \RedBean_OODBBean[]
     */
    protected function runDeleteSql($sql)
    {
        $query = 'DELETE from `' . $this->beanType . ' ';
        if ($this->conditions) {
            $query .= 'where ' . $sql;
        } else {
            $query .= $sql;
        }
        return R::exec($query, $this->bindings);
    }

    protected function buildSql()
    {
        $sql = ' ';
        if ($this->conditions) {
            $sql .= implode(' AND ', $this->conditions) . ' ';
        }
        if ($this->order) {
            $sql .= 'order by ' . $this->order . ' ';
        }
        if (!is_null($this->limit)) {
            $sql .= 'limit ';
            if (!is_null($this->offset)) {
                $sql .= intval($this->offset) . ', ';
            }
            $sql .= intval($this->limit);
        }
        if (trim($sql) === '') {
            $sql = null;
        }
        return $sql;
    }

    /**
     * @return null
     */
    public function getBeanType()
    {
        return $this->beanType;
    }


    public function findWith($attr, $value, $strict = true)
    {
        $this->load();
        foreach ($this->items as $bean) {
            if ($strict && $bean->$attr === $value) {
                return $bean;
            } elseif (!$strict && $bean->$attr == $value) {
                return $bean;
            }
        }
        return null;
    }

    public function toArray()
    {
        $this->load();
        return R::beansToArray($this->items);
    }
}