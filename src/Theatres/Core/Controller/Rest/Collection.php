<?php

namespace Theatres\Core;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Helpers\Api;

/**
 * API abstract elements resource controller.
 *
 * @package Theatres\Core
 */
abstract class Controller_Rest_Collection extends Controller_Rest
{
    /** @const string Key of order param in GET query. */
    const ORDER_KEY = 'order';

    /** @const string Default order. */
    const DEFAULT_ORDER = 'id';

    /** @var array List of allowed orders. */
    protected $allowedOrders = array(
        'id'
    );

    /**
     * Get collection elements as array.
     * GET request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @return array
     */
    public function get(Application $app, Request $request)
    {
        $collection = $this->getCollection();

        $this->applyFilters($collection, $request);

        return $collection->toArray();
    }

    /**
     * Get collection instance.
     *
     * @return Collection
     */
    abstract protected function getCollection();

    /**
     * Fetch and apply filters to collection.
     *
     * @param Collection $collection Collection instance.
     * @param Request $request Request instance.
     * @return void
     */
    protected function applyFilters(Collection $collection, Request $request)
    {
        $order = $this->getOrder($request);

        if ($order) {
            $collection->setOrder($order);
        }
    }

    /**
     * Get order.
     *
     * @param Request $request Request instance.
     * @return string
     */
    protected function getOrder(Request $request)
    {
        $order = $request->query->get(static::ORDER_KEY, static::DEFAULT_ORDER);
        if (!Api::isAllowed($order, $this->allowedOrders)) {
            $order = static::DEFAULT_ORDER;
        }

        return $order;
    }
}