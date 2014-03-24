<?php

namespace Theatres\Core;

use RedBean_Facade as R;
use Theatres\Core\Exceptions\Collection_Beans_UndefinedBeanType as UndefinedBeanType;

abstract class Collection_Beans extends Collection
{
    protected $beanType = null;

    protected function loadData()
    {
        if (!$this->beanType) {
            throw new UndefinedBeanType();
        }

        $sql = $this->buildSql();

        return $this->runLoadSql($sql);
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
     * @param $sql
     * @return array
     */
    protected function runLoadSql($sql)
    {
        return R::findAll($this->beanType, $sql, $this->bindings);
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