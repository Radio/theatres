<?php

namespace Theatres\Models;

use RedBean_Facade as R;
use Theatres\Exceptions\MoreThanOneBeanFound;
use Theatres\Helpers\Models;

/**
 * Class Play
 *
 * @property $id
 * @property $key
 * @property $theatre FK
 * @property $title
 * @property $scene FK
 * @property $link
 *
 * @package Theatres\Models
 */
class Play extends \RedBean_SimpleModel
{
    /**
     * Load play by tag (which is a variant of play title).
     *
     * @param $tag
     * @throws \Theatres\Exceptions\MoreThanOneBeanFound
     */
    public function loadByTag($tag)
    {
        $plays = R::tagged('play', array($tag));
        if ($plays) {
            if (count($plays) == 1) {
                $this->bean->importFrom(current($plays));
            } else {
                throw new MoreThanOneBeanFound('More than one bean found for tag "' . $tag . '"');
            }
        }
    }


    public function update()
    {
        if (!$this->id) {
            if (!$this->key && $this->title) {
                $this->key = Models::generateKey($this->title);
            }
        }
    }
}