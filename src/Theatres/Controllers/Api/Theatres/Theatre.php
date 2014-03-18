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

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title', 'abbr', 'link', 'fetcher', 'key', 'house_slug'
    );
}