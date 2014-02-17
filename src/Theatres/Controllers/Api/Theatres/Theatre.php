<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Controller_Rest;
use Theatres\Core\Exceptions\Api_NotSupported;
use Theatres\Helpers\Api;
use Theatres\Models\Theatre;

class Api_Theatres_Theatre extends Controller_Rest
{
    /** @var Theatre|\RedBean_OODBBean */
    protected $theatre;

    protected static $allowedFields = array(
        'title', 'abbr', 'link', 'fetcher', 'key', 'house_slug'
    );

    /**
     * @param int $id
     */
    protected function loadElement($id)
    {
        $this->theatre = \RedBean_Facade::load('theatre', $id);
    }

    public function get(Application $app, Request $request)
    {
        return $this->theatre->export();
    }

    public function post(Application $app, Request $request)
    {
        throw new Api_NotSupported();
    }

    public function put(Application $app, Request $request)
    {
        $data = $request->request->all();

        foreach ($data as $field => $value) {
            if (Api::isAllowed($field, self::$allowedFields)) {
                $this->theatre->$field = $value;
            }
        }

        if ($this->theatre->isTainted()) {
            \RedBean_Facade::store($this->theatre);
        }

        return '';
    }
}