<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Plays_Play_Tags;
use Theatres\Collections\Tags;
use Theatres\Core\Controller_Rest_Collection;
use Theatres\Core\Exceptions;
use Theatres\Helpers\Api_Request;
use RedBean_Facade as R;

/**
 * API scenes resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Plays_Play_Tags extends Controller_Rest_Collection
{
    /** @var int */
    protected $playId;

    /** @var array List of allowed orders. */
    protected $allowedOrders = array(
        'id', 'title'
    );

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title'
    );

    public function call(Application $app, Request $request, $id = null, $playId = null)
    {
        $this->playId = $playId;
        return parent::call($app, $request, $id, $playId);
    }

    /**
     * Get Tags Collection.
     *
     * @return Tags
     */
    protected function getCollection()
    {
        $collection = new Plays_Play_Tags();
        $collection->setPlayId($this->playId);

        return $collection;
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
        if (!$data || !isset($data['tags']) || !$this->playId) {
            throw new Exceptions\Api_EmptyRequest('Correct data was not found in the request body.');
        }

        $play = R::load('play', $this->playId);
        if ($play->getID()) {
            $setTags = R::tag($play, $data['tags']);

            return ['tags' => $setTags];
        } else {
            throw new Exceptions\Api_OperationFailed('Failed to load play.');
        }
    }
}