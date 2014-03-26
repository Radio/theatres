<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;

/**
 * API shows/show resource controller.
 *
 * @package Shows\Controllers
 */
class Api_Shows_Show extends Controller_Rest_Element
{
    /** @var string Bean type. */
    protected $type = 'show';

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'theatre', 'play', 'scene', 'date', 'price',
    );
}