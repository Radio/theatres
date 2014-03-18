<?php

namespace Theatres\Core;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Exceptions\Api_NotSupported;
use Theatres\Helpers\Api;
use Theatres\Helpers\Api_Request;

/**
 * API abstract elements/element resource controller.
 *
 * @package Theatres\Controllers
 */
abstract class Controller_Rest_Element extends Controller_Rest
{
    /** @var string Bean type. */
    protected $type;

    /** @var \RedBean_OODBBean Element instance. */
    protected $element;

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array();

    /**
     * Load element.
     *
     * @param int $id Element ID.
     * @return void
     */
    protected function loadElement($id)
    {
        $this->element = \RedBean_Facade::load($this->type, $id);
    }

    /**
     * Get element data.
     * GET request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @return array
     */
    public function get(Application $app, Request $request)
    {
        return $this->element->export();
    }

    /**
     * POST request handler.
     * Is not supported for element resource type.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @throws \Theatres\Core\Exceptions\Api_NotSupported
     * @return void
     */
    public function post(Application $app, Request $request)
    {
        throw new Api_NotSupported();
    }

    /**
     * Update element data.
     * PUT request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @return string
     */
    public function put(Application $app, Request $request)
    {
        $data = Api_Request::getPutData($request);

        foreach ($data as $field => $value) {
            if (Api::isAllowed($field, $this->allowedFields)) {
                $this->element->$field = $value;
            }
        }

        if ($this->element->isTainted()) {
            \RedBean_Facade::store($this->element);
        }

        return null;
    }
}