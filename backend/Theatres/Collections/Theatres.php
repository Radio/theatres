<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;

class Theatres extends Beans
{
    public function __construct()
    {
        $this->beanType = 'theatre';
    }

    public function toArray()
    {
        $data = parent::toArray();
        return array_map(function($theatre) {
            $theatre['has_fetcher'] = (bool) $theatre['has_fetcher'];
            return $theatre;
        }, $data);
    }
}