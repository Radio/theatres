<?php

namespace Theatres\Core;

abstract class Collection implements Collection_Interface, \IteratorAggregate, \Countable
{
    protected $items = array();

    protected $isLoaded = false;

    // Filters
    protected $order;
    protected $conditions = array();
    protected $bindings = array();
    protected $offset;
    protected $limit;

    public function getIterator()
    {
        $this->load();
        return new \ArrayIterator($this->items);
    }

    public function count()
    {
        $this->load();
        return count($this->items);
    }

    abstract protected function loadData();

    protected function load()
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $this->beforeLoad();

        $this->items = $this->loadData();

        $this->setIsLoaded();
        $this->afterLoad();
        return $this;
    }

    protected function beforeLoad()
    {

    }

    protected function afterLoad()
    {

    }

    public function getFirst()
    {
        $this->load();
        return reset($this->items);
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function isLoaded()
    {
        return $this->isLoaded;
    }

    protected function setIsLoaded($flag = true)
    {
        $this->isLoaded = $flag;
    }

    /**
     * @param mixed $conditions
     * @param mixed $bindings
     */
    public function setConditions($conditions, $bindings = null)
    {
        if (!is_array($conditions)) {
            $conditions = (array) $conditions;
        }
        $this->conditions = $conditions;
        if (!is_null($bindings)) {
            $this->setBindings($bindings);
        }
    }

    public function addConditions($conditions, $bindings = null)
    {
        if (!is_array($conditions)) {
            $conditions = (array) $conditions;
        }
        $this->conditions = array_merge($this->conditions, $conditions);
        if (!is_null($bindings)) {
            $this->addBindings($bindings);
        }
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param array $bindings
     */
    public function setBindings($bindings)
    {
        $this->bindings = $bindings;
    }

    public function addBindings($bindings)
    {
        $this->bindings = array_merge($this->bindings, $bindings);
    }

    /**
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function toArray()
    {
        return $this->items;
    }
}