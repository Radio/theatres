<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;

class Shows extends Beans
{
    public function __construct()
    {
        $this->beanType = 'show';
    }
}