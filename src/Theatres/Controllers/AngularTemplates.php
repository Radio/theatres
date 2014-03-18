<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Controller_Templatable as Templatable;

class AngularTemplates
{
    use Templatable;

    public function index(Request $request, Application $app, $tpl)
    {
        $path = $app['root']
            . DIRECTORY_SEPARATOR . 'resources'
            . DIRECTORY_SEPARATOR . 'templates'
            . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $tpl)
            . '.html';

        $content = file_get_contents($path);

        return $content;
    }
}