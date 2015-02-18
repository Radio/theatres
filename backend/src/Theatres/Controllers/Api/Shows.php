<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Shows;
use Theatres\Core\Collection;
use Theatres\Core\Controller_Rest_Collection;
use Theatres\Helpers\Api;

/**
 * API shows resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Shows extends Controller_Rest_Collection
{
    /** @var array List of allowed orders. */
    protected $allowedOrders = array(
        'id', 'date'
    );

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'theatre_id', 'play_id', 'scene_id', 'date', 'price',
    );

    /**
     * Get Shows Collection.
     *
     * @return Shows
     */
    protected function getCollection()
    {
        return new Shows();
    }

    /**
     * Apply theatres filter.
     * Apply scene filter.
     * Fetch and apply filters to collection.
     *
     * @param Collection|Shows $collection Collection instance.
     * @param Request $request Request instance.
     * @return void
     */
    protected function applyFilters(Collection $collection, Request $request)
    {
        $theatre = $request->query->get('theatre');
        if ($theatre) {
            $collection->addConditions('`show`.theatre_id = ?', array($theatre));
        }

        $scene = $request->query->get('scene');
        if ($scene) {
            $collection->addConditions('`show`.scene_id = ?', array($scene));
        }

        $populate = explode(',', $request->query->get('populate'));
        if (in_array('play', $populate)) {
            $collection->setPopulatePlayFlag(true);
        }
        if (in_array('theatre', $populate)) {
            $collection->setPopulateTheatreFlag(true);
        }
        if (in_array('scene', $populate)) {
            $collection->setPopulateSceneFlag(true);
        }

        $this->applyDateFilters($collection, $request);
        $this->applyTimeFilters($collection, $request);

        parent::applyFilters($collection, $request);
    }

    /**
     * Apply date filters.
     *
     * @param Collection $collection Collection instance.
     * @param Request $request Request instance.
     * @return void
     */
    protected function applyDateFilters(Collection $collection, Request $request)
    {
        $date = Api::toSqlDate($request->query->get('date'));
        if ($date) {
            $collection->addConditions('date(`date`) = ?', array($date));
        }

        $dateFrom = Api::toSqlDate($request->query->get('date_from'));
        if ($dateFrom) {
            $collection->addConditions('date(`date`) >= ?', array($dateFrom));
        }

        $dateTo = Api::toSqlDate($request->query->get('date_to'));
        if ($dateTo) {
            $collection->addConditions('date(`date`) <= ?', array($dateTo));
        }
    }

    /**
     * Apply time filters.
     *
     * @param Collection $collection Collection instance.
     * @param Request $request Request instance.
     * @return void
     */
    protected function applyTimeFilters(Collection $collection, Request $request)
    {
        $time = Api::toSqlTime($request->query->get('time'));
        if ($time) {
            $collection->addConditions('time(`date`) = ?', array($time));
        }

        $timeFrom = Api::toSqlTime($request->query->get('time_from'));
        if ($timeFrom) {
            $collection->addConditions('time(`date`) >= ?', array($timeFrom));
        }

        $timeTo = Api::toSqlTime($request->query->get('time_to'));
        if ($timeTo) {
            $collection->addConditions('time(`date`) <= ?', array($timeTo));
        }
    }
}