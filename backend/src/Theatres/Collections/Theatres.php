<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;
use Theatres\Models\Theatre;

class Theatres extends Beans
{
    public function __construct()
    {
        $this->beanType = 'theatre';
        static::$booleanFields = Theatre::$booleanFields;
    }
}