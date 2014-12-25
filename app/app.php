<?php

if (!is_readable(__DIR__  . '/config.php')) {
    die('Configuration file is not available.');
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/bootstrap.php';

// 1. Create the Application

$app = new Silex\Application();
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
if ($secure) {
    require_once __DIR__ . '/security.php';
}

$app['debug'] = $debug;
$app['dir.root'] = realpath(__DIR__ . '/..');
$app['dir.resources'] = $app['dir.root'] . '/resources';

$app['assets'] = $app->share(function() use ($siteBase) {
    return new \Theatres\Helpers\Assets($siteBase);
});

// 1.1 Set error handler

$errorHandler = new \Theatres\Core\ErrorHandler();
if (!$debug) {
    $app->error(array($errorHandler, 'handleKernelException'));
}

// 2. Configure ORM

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
    $app->after(function (
            \Symfony\Component\HttpFoundation\Request $request,
            \Symfony\Component\HttpFoundation\Response $response
        ) use ($app, $queryLogger) {
            $debugTemplatePath = $app['dir.resources']
                . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'debug.html';
            $debugHtml = file_get_contents($debugTemplatePath);

            $response->setContent(
                $response->getContent()
                . sprintf($debugHtml, implode("\n", $queryLogger->getLogs()))
            );
        });
}

// 3. Define Routes

require_once __DIR__ . '/routes.php';

return $app;