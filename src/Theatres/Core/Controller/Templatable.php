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
    /** @var string Layout suffix which is used to resolve naming conflicts */
    protected $layoutSuffix = '.layout.twig';

    /** @var string layout name */
    protected $layout;

    /**
     * Render template in a context.
     *
     * @param string $templateName Name (path) of the template file.
     * @param array  $context      Rendering context.
     * @param \Silex\Application $app Application instance.
     * @return string
     */
    protected function renderTemplate($templateName, array $context, Application $app)
    {
        if ($this->layout) {
            $context['template'] = $templateName;
            $template = $this->layout . $this->layoutSuffix;
        } else {
            $template = $templateName;
        }

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        return $twig->render($template, $context);
    }

    /**
     * @param string $layout Layout name
     * @return void
     */
    public function useLayout($layout)
    {
        $this->layout = $layout;
    }
}