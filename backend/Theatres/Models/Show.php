<?php

namespace Theatres\Models;

use Theatres\Collections\Theatres;
use Theatres\Collections\Scenes;

/**
 * Class Show
 *
 * @property $id
 * @property $theatre FK
 * @property $play FK
 * @property $scene FK
 * @property $date
 * @property $hash
 * @property $price
 *
 * @package Theatres\Models
 */
class Show extends \RedBean_SimpleModel
{
    public function update()
    {
        $this->hash = $this->generateHash();
    }

    protected function generateHash()
    {
        $line = sprintf('%s-%s-%s-%s',
            $this->theatre,
            $this->play,
            $this->scene,
            $this->date
        );
        return md5($line);
    }
}