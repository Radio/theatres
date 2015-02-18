<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Controller_Rest_Element;
use Theatres\Models\Play;

/**
 * API plays/play resource controller.
 *
 * @package Plays\Controllers
 */
class Api_Plays_Play extends Controller_Rest_Element
{
    /** @var string Bean type. */
    protected $type = 'play';

    /** @var string|null The field containing the unique name of an element. Is used to load element by @name. */
    protected $nameField = 'key';

    public function __construct()
    {
        $this->allowedFields = Play::$allowedFields;
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
        $play = $this->element->export();
        $play['is_premiere'] = (bool) $play['is_premiere'];
        $play['is_for_children'] = (bool) $play['is_for_children'];
        $play['is_musical'] = (bool) $play['is_musical'];
        return $play;
    }
}