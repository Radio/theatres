<?php

$debug = true;

require_once __DIR__.'/bootstrap.php';

use RedBean_Facade as R;

// 1. Configure Twig
$twigLoader = new Twig_Loader_Filesystem(__DIR__.'/../resources/templates');
$twig = new Twig_Environment($twigLoader, array(
    'cache' => $debug ? false : __DIR__.'/../resources/templates_cache',
    'debug' => $debug
));

// 2. Configure ORM
R::setup('mysql:host=localhost;dbname=theatres', 'root', 'root');
//R::freeze(true);
define('REDBEAN_MODEL_PREFIX', '\\Theatres\\Models\\');


// 3. Configure Application
$app = new Silex\Application(array(
    'twig' => $twig
));
$app['debug'] = $debug;

// 2. Define Routes
$app->get('/', 'Theatres\\Controllers\\Homepage::index')
    ->bind('homepage');

$app->get('/fetch', 'Theatres\\Controllers\\Fetch::index')
    ->bind('fetch');

$app->get('/fetch/{theatre}', 'Theatres\\Controllers\\Fetch::index')
    ->bind('fetch_theatre');


return $app;