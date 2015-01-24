<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;

/**
 * API theatres/theatre resource controller.
 *
 * @package Theatres\Controllers
 */
class Api_Theatres_Theatre extends Controller_Rest_Element
{
    /** @var string Bean type. */
    protected $type = 'theatre';

    /** @var string|null The field containing the unique name of an element. Is used to load element by @name. */
    protected $nameField = 'key';

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title', 'abbr', 'link', 'has_fetcher', 'key', 'house_slug'
    );
}