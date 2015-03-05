<?php

namespace Theatres\Controllers;

use Silex\Application;
use Theatres\Collections\Scenes;
use Theatres\Core\Controller_Rest_Collection;
use Theatres\Models\Scene;

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

    /**
     * Set allowed fields.
     */
    public function __construct()
    {
        $this->allowedFields = Scene::$allowedFields;
    }

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