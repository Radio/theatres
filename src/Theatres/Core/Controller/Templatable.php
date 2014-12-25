<?php

namespace Theatres\Core;

use Silex\Application;

/**
 * Add renderTemplate functionality.
 *
 * @package Theatres\Core
 */
trait Controller_Templatable
{
    /** @var string Layout folder */
    protected $layoutFolder = 'layouts';

    /**
     * Render template in a context.
     *
     * @param string             $layoutName Name (path) of the layout file.
     * @param \Silex\Application $app        Application instance.
     *
     * @return string
     */
    protected function renderLayout($layoutName, Application $app)
    {
        $content = '';
        $path = $app['dir.resources']
            . DIRECTORY_SEPARATOR . $this->layoutFolder
            . DIRECTORY_SEPARATOR . $layoutName . '.html';
        if (is_readable($path)) {
            $content = file_get_contents($path);
        }

        return $content;
    }
}