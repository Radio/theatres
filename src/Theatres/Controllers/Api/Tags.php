<?php

namespace Theatres\Controllers;

use Silex\Application;
use Theatres\Collections\Tags;
use Theatres\Core\Controller_Rest_Collection;

/**
 * API tags resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Tags extends Controller_Rest_Collection
{
    /** @var array List of allowed orders. */
    protected $allowedOrders = array(
        'id', 'title'
    );

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title'
    );

    /**
     * Get Tags Collection.
     *
     * @return Tags
     */
    protected function getCollection()
    {
        return new Tags();
    }
}