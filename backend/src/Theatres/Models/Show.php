<?php

namespace Theatres\Models;

use Theatres\Collections\Shows;
use Theatres\Core\Model_Bean;

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
class Show extends Model_Bean
{
    public static $allowedFields = [
        'theatre_id', 'theatre', 'play_id', 'play', 'scene_id', 'scene', 'price', 'date'
    ];

    public function update()
    {
        $this->hash = $this->generateHash();
    }

    public function loadByHash($hash)
    {
        $shows = new Shows();
        $shows->setConditions('`hash` = ?', array($hash));

        $first = $shows->getFirst();
        if ($first) {
            $this->bean->importFrom($first);
        }
    }

    public function generateHash()
    {
        $line = sprintf('%s-%s-%s-%s',
            $this->theatre->id,
            $this->play->id,
            $this->scene->id,
            $this->date
        );
        return md5($line);
    }
}