<?php

namespace Theatres\Core;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Helpers\Api;
use Theatres\Helpers\Api_Request;
use RedBean_Facade as R;

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

    /** @var string|null The field containing the unique name of an element. Is used to load element by @name. */
    protected $nameField;

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array();

    /**
     * Load element.
     *
     * @param int $id Element ID.
     * @throws Exceptions\Api_NotFound
     * @return void
     */
    protected function loadElement($id)
    {
        if ($this->nameField && strpos($id, '@') === 0) {
            $name = substr($id, 1);
            $this->element = R::findOne($this->type, '`' . $this->nameField . '`=?', array($name));
            if (!$this->element) {
                $this->element = R::dispense($this->type);
            }
        } else {
            $this->element = R::load($this->type, $id);
        }
        if ($id && !$this->element->getID()) {
            throw new Exceptions\Api_NotFound(ucfirst($this->type) . ' was not found');
        }
    }

    /**
     * Get element data.
     * GET request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @return array|null
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
     * @throws Exceptions\Api_NotSupported
     * @return void
     */
    public function post(Application $app, Request $request)
    {
        throw new Exceptions\Api_NotSupported();
    }

    /**
     * Update element data.
     * PUT request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @return null
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
            R::store($this->element);
        }

        return null;
    }

    /**
     * Delete element.
     * DELETE request handler.
     *
     * @param Application $app Application instance.
     * @param Request $request Request instance.
     * @return null
     */
    public function delete(Application $app, Request $request)
    {
        R::trash($this->element);

        return null;
    }
}