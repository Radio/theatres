<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Controller_Templatable as Templatable;

class Admin_Homepage
{
    use Templatable;

    public function index(Request $request, Application $app)
    {
        $context = array(
            'is_admin' => true
        );

        $this->useLayout('admin');
        return $this->renderTemplate('admin/homepage.twig', $context, $app);
    }
}