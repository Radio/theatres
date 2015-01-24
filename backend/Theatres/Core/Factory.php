<?php

namespace Theatres\Core;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;

/**
 * Config Class working with yaml configuration.
 * @package Theatres\Core
 */
class Factory
{
    /** @var \Silex\Application Application instance. */
    protected $app;

    /**
     * Set Application instance.
     *
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $theatreKey Theatre key.
     * @throws Exceptions\Fetchers_UndefinedFetcher
     *
     * @return Fetcher
     */
    public function getTheatreFetcher($theatreKey)
    {
        /** @var Config $config */
        $config = $this->app['config'];
        $theatresConfig = $config->get('theatres');

        $fetcherClassName = $theatresConfig[$theatreKey]['fetcher'];

        if (class_exists($fetcherClassName)) {
            return new $fetcherClassName;
        } else {
            throw new Fetchers_UndefinedFetcher("Fetcher '$fetcherClassName' is not defined.");
        }
    }
}