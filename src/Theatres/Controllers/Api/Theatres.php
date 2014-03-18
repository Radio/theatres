<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Theatres;
use Theatres\Core\Collection;
use Theatres\Core\Controller_Rest_Collection;
use Theatres\Helpers\Api;

/**
 * API theatres resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Theatres extends Controller_Rest_Collection
{
    /** @var array List of allowed orders. */
    protected $allowedOrders = array(
        'id', 'title'
    );

    /**
     * Get Theatres Collection.
     *
     * @return Theatres
     */
    protected function getCollection()
    {
        return new Theatres();
    }

    /**
     * Apply 'fetchable' filter.
     * Fetch and apply filters to collection.
     *
     * @param Collection $collection Collection instance.
     * @param Request $request Request instance.
     */
    protected function applyFilters(Collection $collection, Request $request)
    {
        $fetchable = Api::toBool($request->query->get('fetchable'));
        if ($fetchable !== null) {
            if ($fetchable) {
                $collection->addConditions('fetcher is not null and fetcher != ""');
            } else {
                $collection->addConditions('(fetcher is null or fetcher = "")');
            }
        }

        parent::applyFilters($collection, $request);
    }
}