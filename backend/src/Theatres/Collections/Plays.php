<?php

namespace Theatres\Collections;

use Theatres\Core\Collection_Beans as Beans;

class Plays extends Beans
{
    public function __construct()
    {
        $this->beanType = 'play';
    }

    public function toArray()
    {
        $data = parent::toArray();
        return array_map(function($play) {
            $play['is_premiere'] = (bool) $play['is_premiere'];
            $play['is_for_children'] = (bool) $play['is_for_children'];
            $play['is_musical'] = (bool) $play['is_musical'];
            return $play;
        }, $data);
    }
}