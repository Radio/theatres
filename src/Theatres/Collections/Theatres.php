<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;

class Theatres extends Beans
{
    public function __construct()
    {
        $this->beanType = 'theatre';
    }
}