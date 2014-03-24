<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;

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

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title', 'date', 'link', 'price', 'scene', 'theatre'
    );
}