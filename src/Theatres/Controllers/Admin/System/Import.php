<?php

namespace Theatres\Controllers;

use Silex\Application;
use RedBean_Facade as R;
use Symfony\Component\Yaml\Yaml;
use Theatres\Exceptions\Import as ImportException;

class Admin_System_Import
{
    protected $importPath;

    public function index(Application $app)
    {
        $this->setImportPath($app['root'] . DIRECTORY_SEPARATOR . 'resources'
            . DIRECTORY_SEPARATOR . 'export');
        $this->validateImportPath();

        $this->importTheatres();
        $this->importScenes();
        $this->importPlays();

        return 'Import was successful.';
    }

    protected function importTheatres()
    {
        $this->importItems('theatre', 'theatres.yaml');
    }

    protected function importScenes()
    {
        $this->importItems('scene', 'scenes.yaml');
    }

    protected function importPlays()
    {
        $this->importItems('play', 'plays.yaml');
    }

    protected function importItems($type, $fileName)
    {
        $filePath = $this->getImportPath() . DIRECTORY_SEPARATOR . $fileName;
        $itemsData = Yaml::parse($filePath);

        R::wipe($type);
        $beans = array();
        foreach ($itemsData as $itemData) {
            unset($itemData['id']);
            $bean = R::dispense($type);
            $bean->import($itemData);
            $beans[] = $bean;
        }
        R::storeAll($beans);
    }

    protected function validateImportPath()
    {
        $importPath = $this->getImportPath();
        $importDirectoryIsWritable = is_readable($importPath);
        if (!$importDirectoryIsWritable) {
            throw new ImportException('Import directory "' . $importPath . '" is not readable.');
        }
    }

    /**
     * @param mixed $exportPath
     */
    public function setImportPath($exportPath)
    {
        $this->importPath = $exportPath;
    }

    /**
     * @return mixed
     */
    public function getImportPath()
    {
        return $this->importPath;
    }
}