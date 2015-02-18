<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;
use Theatres\Models\Theatre;

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

    public function __construct()
    {
        $this->allowedFields = Theatre::$allowedFields;
    }
}