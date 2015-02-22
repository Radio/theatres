<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Controller_Rest_Collection;
use Theatres\Core\Exceptions\Api_NotSupported;
use Theatres\Helpers\Api_Request;
use Theatres\Core\Exceptions;
use RedBean_Facade as R;
use Theatres\Models\Play;

/**
 * API scenes resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Plays_Play_Duplicates extends Controller_Rest_Collection
{
    /** @var int */
    protected $playId;

    public function call(Application $app, Request $request, $id = null, $playId = null)
    {
        $this->playId = $playId;
        return parent::call($app, $request, $id, $playId);
    }

    public function getCollection()
    {
        throw new Api_NotSupported();
    }

    public function post(Application $app, Request $request)
    {
        $data = Api_Request::getPostData($request);
        if (!$data) {
            throw new Exceptions\Api_EmptyRequest('Correct data was not found in request body.');
        }

        /** @var Play $original */
        $original = R::load('play', $this->playId);
        /** @var Play $duplicate */
        $duplicate = R::load('play', $data['duplicate']);
        if (!$original->getID() || !$duplicate->getID()) {
            throw new Exceptions\Api_NotFound('Play was not found');
        }

        $original->absorbDuplicate($duplicate);

        return ['id' => 0];
    }
}