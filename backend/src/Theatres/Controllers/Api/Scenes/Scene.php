<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;
use Theatres\Models\Scene;

/**
 * API scenes/scene resource controller.
 *
 * @package Scenes\Controllers
 */
class Api_Scenes_Scene extends Controller_Rest_Element
{
    /** @var string Bean type. */
    protected $type = 'scene';

    /** @var string|null The field containing the unique name of an element. Is used to load element by @name. */
    protected $nameField = 'key';

    public function __construct()
    {
        $this->allowedFields = Scene::$allowedFields;
    }
}