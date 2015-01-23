<?php

namespace Theatres\Controllers;

use Silex\Application;
use RedBean_Facade as R;
use Symfony\Component\Yaml\Yaml;
use Theatres\Exceptions\Export as ExportException;

class System_Export
{
    protected $exportPath;

    public function index(Application $app)
    {
        $this->setExportPath($app['dir.root'] . DIRECTORY_SEPARATOR . 'resources'
            . DIRECTORY_SEPARATOR . 'export');
        $this->validateExportPath();

        $this->exportTheatres();
        $this->exportScenes();
        $this->exportPlays();

        return 'Export was successful.';
    }

    protected function exportTheatres()
    {
        return $this->exportItems('theatre', 'theatres.yaml');
    }

    protected function exportScenes()
    {
        return $this->exportItems('scene', 'scenes.yaml');
    }

    protected function exportPlays()
    {
        return $this->exportItems('play', 'plays.yaml');
    }

    protected function exportItems($type, $fileName)
    {
        $items = R::findAll($type, 'order by id');
        $yaml = Yaml::dump(R::beansToArray($items));
        $filePath = $this->getExportPath() . DIRECTORY_SEPARATOR . $fileName;

        return file_put_contents($filePath, $yaml);
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