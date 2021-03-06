<?php

namespace Theatres\Controllers;

use Silex\Application;
use Theatres\Collections\Scenes;
use Theatres\Core\Controller_Rest_Collection;

/**
 * API scenes resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Scenes extends Controller_Rest_Collection
{
    /** @var array List of allowed orders. */
    protected $allowedOrders = array(
        'id', 'title'
    );

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title', 'key'
    );

    /**
     * Get Scenes Collection.
     *
     * @return Scenes
     */
    protected function getCollection()
    {
        return new Scenes();
    }
}