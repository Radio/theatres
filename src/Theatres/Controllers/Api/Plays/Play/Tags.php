<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Plays_Play_Tags;
use Theatres\Collections\Tags;
use Theatres\Core\Controller_Rest_Collection;

/**
 * API scenes resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Plays_Play_Tags extends Controller_Rest_Collection
{
    /** @var int */
    protected $playId;

    /** @var array List of allowed orders. */
    protected $allowedOrders = array(
        'id', 'title'
    );

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title'
    );

    public function call(Application $app, Request $request, $id = null, $playId = null)
    {
        $this->playId = $playId;
        return parent::call($app, $request, $id, $playId);
    }

    /**
     * Get Tags Collection.
     *
     * @return Tags
     */
    protected function getCollection()
    {
        $collection = new Plays_Play_Tags();
        $collection->setPlayId($this->playId);

        return $collection;
    }
}