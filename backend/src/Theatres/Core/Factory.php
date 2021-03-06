<?php

namespace Theatres\Core;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;
use Theatres\Exceptions\UndefinedSchedule;
use Theatres\Models\Schedule;
use Theatres\Models\Theatre;

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
     * Get fetcher for theatre.
     *
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

        if (isset($theatresConfig[$theatreKey])) {
            $fetcherClassName = $theatresConfig[$theatreKey]['fetcher'];
            if (class_exists($fetcherClassName)) {
                return new $fetcherClassName;
            }
            throw new Fetchers_UndefinedFetcher("Fetcher '$fetcherClassName' is not defined.");
        }
        throw new Fetchers_UndefinedFetcher("Fetcher is not defined.");
    }

    /**
     * Get schedule for theatre.
     *
     * @param Theatre $theatre Theatre Beam.
     * @throws \Theatres\Exceptions\UndefinedSchedule
     * @return Schedule
     */
    public function getTheatreSchedule(Theatre $theatre)
    {
        /** @var Config $config */
        $config = $this->app['config'];
        $theatresConfig = $config->get('theatres');

        if (isset($theatresConfig[$theatre->key])) {
            $scheduleClassName = $theatresConfig[$theatre->key]['schedule'];
            if (class_exists($scheduleClassName)) {
                return new $scheduleClassName($theatre);
            }
            throw new UndefinedSchedule("Schedule '$scheduleClassName' is not defined.");
        }
        throw new UndefinedSchedule("Schedule is not defined.");
    }
}