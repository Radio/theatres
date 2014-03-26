<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;

class Tags extends Beans
{
    public function __construct()
    {
        $this->beanType = 'tag';
    }
}