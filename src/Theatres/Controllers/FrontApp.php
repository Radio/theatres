<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Controller_Templatable as Templatable;

class FrontApp
{
    use Templatable;

    public function index(Request $request, Application $app)
    {
        $context = array();

        $this->useLayout('front_app');
        return $this->renderTemplate('front_app.twig', $context, $app);
    }
}