<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/bootstrap.php';

// 1. Create the Application

$app = new Silex\Application();
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

if ($secure) {
    $app->register(new Silex\Provider\SecurityServiceProvider(), array(
        'security.firewalls' => array(
            'admin' => array(
                'pattern' => '^/admin',
                'http' => true,
                'users' => array(
                    // raw password is foo
                    'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
                ),
            ),
        )
    ));
}

$app['debug'] = $debug;

// 1.1 Set error handler

$errorHandler = new \Theatres\Core\ErrorHandler();
//$app->error(array($errorHandler, 'handleKernelException'));

// 2. Configure Twig

$twigLoader = new Twig_Loader_Filesystem(__DIR__.'/../resources/templates');
$twig = new Twig_Environment($twigLoader, array(
    'cache' => $debug ? false : __DIR__.'/../resources/templates_cache',
    'debug' => $debug
));
if ($debug) {
    $twig->addExtension(new Twig_Extension_Debug());
}

$twig->addFilter('dayOfWeekName', new Twig_Filter_Function('\Theatres\Helpers\Date::getDayOfWeekName'));
$twig->addFilter('monthName', new Twig_Filter_Function('\Theatres\Helpers\Date::getMonthName'));
$twig->addFilter('theatreTitle', new Twig_Filter_Function('\Theatres\Helpers\Models::getTheatreTitle'));

$twig->addGlobal('app', $app);
$twig->addGlobal('debug', $debug);
$twig->addGlobal('base', $siteBase);
$twig->addGlobal('appTitle', $appTitle);

$app['twig'] = $twig;

// 3. Configure ORM

define('REDBEAN_MODEL_PREFIX', '\\Theatres\\Models\\');

RedBean_Facade::setup(sprintf('mysql:host=%s;dbname=%s', $db['host'], $db['name']), $db['user'], $db['pass']);
//RedBean_Facade::freeze(true);
//RedBean_Facade::debug($debug);
RedBean_Facade::exec('SET NAMES utf8');
//RedBean_Facade::exec('SET CHARACTER_SET utf8');

if ($debug) {
    $queryLogger = RedBean_Plugin_QueryLogger::getInstanceAndAttach(
        RedBean_Facade::getDatabaseAdapter()
    );
    $app['queryLogger'] = $queryLogger;
    if ($app['twig']) {
        $app['twig']->addGlobal('queryLogger', $queryLogger);
    }
}

// 4. Define Routes

require_once __DIR__ . '/routes.php';

return $app;