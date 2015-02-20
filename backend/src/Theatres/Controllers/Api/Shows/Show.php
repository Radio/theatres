<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;
use Theatres\Models\Show;

/**
 * API shows/show resource controller.
 *
 * @package Shows\Controllers
 */
class Api_Shows_Show extends Controller_Rest_Element
{
    /** @var string Bean type. */
    protected $type = 'show';

    public function __construct()
    {
        $this->allowedFields = Show::$allowedFields;
    }
}