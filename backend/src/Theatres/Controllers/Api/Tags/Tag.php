<?php

namespace Theatres\Controllers;

use Theatres\Core\Controller_Rest_Element;

/**
 * API tags/tag resource controller.
 *
 * @package Tags\Controllers
 */
class Api_Tags_Tag extends Controller_Rest_Element
{
    /** @var string Bean type. */
    protected $type = 'tag';

    /** @var string|null The field containing the unique name of an element. Is used to load element by @name. */
    protected $nameField = 'key';

    /** @var array Fields that are allowed to update. */
    protected $allowedFields = array(
        'title', 'key'
    );
}