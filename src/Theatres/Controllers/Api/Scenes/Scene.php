<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;

/**
 * API scenes/scene resource controller.
 *
 * @package Scenes\Controllers
 */
class Api_Scenes_Scene extends Controller_Rest_Element
{
    /** @var string Bean type. */
    protected $type = 'scene';

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title', 'key'
    );
}