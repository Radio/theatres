<?php

namespace Theatres\Core;

use Symfony\Component\Yaml\Yaml;

/**
 * Config Class working with yaml configuration.
 * @package Theatres\Core
 */
class Config
{
    /**
     * @var string path to config files.
     */
    protected $configPath;

    /**
     * @var array Loaded Configuration.
     */
    protected $loadedConfig = [];

    public function __construct($configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * Load configuration from yaml.
     *
     * @param string $key Configuration key.
     */
    public function load($key)
    {
        $filePath = $this->configPath . DIRECTORY_SEPARATOR . $key . '.yaml';
        $this->loadedConfig[$key] = Yaml::parse($filePath);
    }

    /**
     * Get config. Load if not loaded.
     *
     * @param string $key Configuration key.
     * @return mixed
     */
    public function get($key)
    {
        if (!isset($this->loadedConfig[$key])) {
            $this->load($key);
        }
        return $this->loadedConfig[$key];
    }
}