<?php

namespace Theatres\Models;

trait Scene_Populatable
{
    /**
     * @var bool Flag which indicates that we should populate the scene.
     */
    protected $populateSceneFlag = false;

    /**
     * @var array List of fields to populate.
     */
    protected $scenePopulatingFields = ['title', 'key'];

    /**
     * Set populate flag.
     *
     * @param boolean $populateSceneFlag Yes or No.
     */
    public function setPopulateSceneFlag($populateSceneFlag)
    {
        $this->populateSceneFlag = $populateSceneFlag;
    }

    /**
     * Adjust sql statements so that scene fields are added.
     *
     * @param $selectStatement
     * @param $fromStatement
     * @param $mainTable
     */
    protected function populateScene(&$selectStatement, &$fromStatement, $mainTable)
    {
        foreach ($this->scenePopulatingFields as $sceneField) {
            $selectStatement .= ', scene.`' . $sceneField . '` as scene_' . $sceneField;
        }
        $fromStatement .= ' LEFT JOIN scene on ' . $mainTable . '.scene_id = scene.`id`';
    }
}