<?php

namespace Theatres\Models;

use Theatres\Collections\Scenes;
use Theatres\Core\Model_Bean;

/**
 * Class Scene
 *
 * @property $id
 * @property $title
 * @property $key
 *
 * @package Theatres\Models
 */
class Scene extends Model_Bean
{
    public static $allowedFields = ['title', 'key'];

    public function loadByKey($key)
    {
        $scenes = new Scenes();
        $scenes->setConditions('`key` = ?', array($key));

        $first = $scenes->getFirst();
        if ($first) {
            $this->bean->importFrom($first);
        }
    }
}