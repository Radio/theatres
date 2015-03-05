<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Plays;
use Theatres\Core\Collection;
use Theatres\Core\Controller_Rest_Collection;
use Theatres\Helpers\Api;
use Theatres\Models\Play;

/**
 * API plays resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Plays extends Controller_Rest_Collection
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
        $this->allowedFields = Play::$allowedFields;
    }

    /**
     * Get Plays Collection.
     *
     * @return Plays
     */
    protected function getCollection()
    {
        return new Plays();
    }

    /**
     * Apply theatres filter.
     * Apply scene filter.
     * Fetch and apply filters to collection.
     *
     * @param Collection|Plays $collection Collection instance.
     * @param Request $request Request instance.
     * @return void
     */
    protected function applyFilters(Collection $collection, Request $request)
    {
        $theatre = $request->query->get('theatre');
        if ($theatre) {
            $collection->addConditions('theatre_id = ?', array($theatre));
        }

        $scene = $request->query->get('scene');
        if ($scene) {
            $collection->addConditions('scene_id = ?', array($scene));
        }

        $populate = explode(',', $request->query->get('populate'));
        if (in_array('theatre', $populate)) {
            $collection->setPopulateTheatreFlag(true);
        }
        if (in_array('scene', $populate)) {
            $collection->setPopulateSceneFlag(true);
        }

        parent::applyFilters($collection, $request);
    }
}