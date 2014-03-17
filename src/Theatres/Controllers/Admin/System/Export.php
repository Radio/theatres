<?php

namespace Theatres\Controllers;

use Silex\Application;
use Theatres\Collections\Scenes;
use Theatres\Collections\Theatres;
use Symfony\Component\Yaml\Yaml;
use Theatres\Exceptions\Export as ExportException;

class Admin_System_Export
{
    protected $exportPath;

    public function index(Application $app)
    {
        $this->setExportPath($app['root'] . DIRECTORY_SEPARATOR . 'resources'
            . DIRECTORY_SEPARATOR . 'export');
        $this->validateExportPath();

        $this->exportTheatres();
        $this->exportScenes();

        return 'Export was successful.';
    }

    protected function exportTheatres()
    {
        $theatres = new Theatres();
        $theatres->setOrder('id');

        $yaml = Yaml::dump($theatres->toArray());

        $filePath = $this->getExportPath() . DIRECTORY_SEPARATOR . 'theatres.yaml';
        file_put_contents($filePath, $yaml);
    }

    protected function exportScenes()
    {
        $scenes = new Scenes();
        $scenes->setOrder('id');

        $yaml = Yaml::dump($scenes->toArray());

        $filePath = $this->getExportPath() . DIRECTORY_SEPARATOR . 'scenes.yaml';
        file_put_contents($filePath, $yaml);
    }

    protected function validateExportPath()
    {
        $exportPath = $this->getExportPath();
        $exportDirectoryExists = file_exists($exportPath);
        if (!$exportDirectoryExists) {
            mkdir($exportPath, 0777, true);
            chmod($exportPath, 0777);
        }
        $exportDirectoryIsWritable = is_writable($exportPath);
        if (!$exportDirectoryIsWritable) {
            throw new ExportException('Export directory "' . $exportPath . '" is not writable.');
        }
    }

    /**
     * @param mixed $exportPath
     */
    public function setExportPath($exportPath)
    {
        $this->exportPath = $exportPath;
    }

    /**
     * @return mixed
     */
    public function getExportPath()
    {
        return $this->exportPath;
    }
}