<?php

namespace Theatres\Helpers;

use Theatres\Collections\Theatres;

class Models
{
    public static function getTheatreTitle($theatreKey)
    {
        $theatres = new Theatres();
        return $theatres->findWith('key', $theatreKey)->title;
    }
}