<?php

namespace Theatres\Controllers;

use Silex\Application;
use RedBean_Facade as R;

class Homepage
{
    public function index(Application $app)
    {
        $plays = R::findAll('play', 'order by `date`');

        $context = array(
            'base' => '/theatres/web',
            'plays' => $plays
        );

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        return $twig->render('homepage.html', $context);
    }
}