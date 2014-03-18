<?php

namespace Theatres\Core;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Helpers\Api;
use Theatres\Helpers\Api_Request;
use RedBean_Facade as R;

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

    /** @var array Fields that are allowed when creating element. */
    protected $allowedFields = array();

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
     * @return Collection_Beans
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

    /**
     * Create new element
     * POST request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @throws Exceptions\Api_OperationFailed
     * @throws Exceptions\Api_EmptyRequest
     * @return array
     */
    public function post(Application $app, Request $request)
    {
        $data = Api_Request::getPostData($request);
        if (!$data) {
            throw new Exceptions\Api_EmptyRequest('Correct data was not found in request body.');
        }

        $beanType = $this->getCollection()->getBeanType();

        $element = R::dispense($beanType);

        foreach ($data as $field => $value) {
            if (Api::isAllowed($field, $this->allowedFields)) {
                $element->$field = $value;
            }
        }

        if ($element->isTainted()) {
            $elementId = R::store($element);

            return array(
                'id' => $elementId
            );
        } else {
            throw new Exceptions\Api_OperationFailed('Failed to store ' . $beanType . '.');
        }
    }

    /**
     * Bulk update elements.
     * PUT request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @throws Exceptions\Api_EmptyRequest
     * @throws Exceptions\Api_NotImplemented
     * @return array
     */
    public function put(Application $app, Request $request)
    {
        $data = Api_Request::getPostData($request);
        if (!$data) {
            throw new Exceptions\Api_EmptyRequest('Correct data was not found in request body.');
        }
        // todo: implement bulk update
        throw new Exceptions\Api_NotImplemented();
    }

    /**
     * Delete all elements.
     * DELETE request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @throws Exceptions\Api_NotImplemented
     * @return array
     */
    public function delete(Application $app, Request $request)
    {
        $beanType = $this->getCollection()->getBeanType();
        R::wipe($beanType);

        return null;
    }
}