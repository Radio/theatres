<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Admin_Homepage
{
    public function index(Request $request, Application $app)
    {

        $availableTheatres = array(
            ''
        );

        $context = array(
            'is_admin' => true
        );

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        return $twig->render('admin/homepage.html', $context);
    }
}