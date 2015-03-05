<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Theatres;
use Theatres\Core\Collection;
use Theatres\Core\Controller_Rest_Collection;
use Theatres\Helpers\Api;
use Theatres\Models\Theatre;

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
     * Set allowed fields.
     */
    public function __construct()
    {
        $this->allowedFields = Theatre::$allowedFields;
    }

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
                $collection->addConditions('has_fetcher = 1');
            } else {
                $collection->addConditions('has_fetcher = 0');
            }
        }

        parent::applyFilters($collection, $request);
    }
}