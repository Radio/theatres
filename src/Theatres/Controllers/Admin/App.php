<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Controller_Templatable as Templatable;

class Admin_App
{
    use Templatable;

    public function index(Request $request, Application $app)
    {
        return $this->renderLayout('admin', $app);
    }
}