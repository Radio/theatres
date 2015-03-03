<?php

namespace Theatres\Models;

use RedBean_Facade as R;

/**
 * Theatre Schedule
 * @package Theatres\Models
 */
class Schedule
{
    /** @var Theatre */
    protected $theatre;

    /** @var Theatre[] */
    protected $theatresCache;

    /** @var Scene[] */
    protected $scenesCache;

    /**
     * Set theatre instance.
     *
     * @param Theatre $theatre
     */
    public function __construct(Theatre $theatre)
    {
        $this->theatre = $theatre;
    }

    /**
     * Save fetched shows.
     *
     * @param array $showsData Shows Data from fetcher.
     * @param int $month
     * @param int $year
     */
    public function saveSchedule($showsData, $month, $year)
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            // do not edit old schedules.
            return;
        }
        $this->clearSchedule($month, $year, $showsData);

        $shows = array();
        foreach ($showsData as $showData) {
            $this->prepareShowData($showData);
            $show = $this->setupShow($showData);
            if ($show) {
                $shows[] = $show;
            }
        }
        R::storeAll($shows);
    }

    /**
     * Populate scene and theatre by key.
     *
     * @param &array $showData Show Data from fetcher.
     */
    protected function prepareShowData(&$showData)
    {
        $this->populateTheatre($showData);
        $this->populateScene($showData);
    }

    /**
     * Populate theatre by key.
     *
     * @param &array $showData Link to $showData array.
     */
    protected function populateTheatre(&$showData)
    {
        if (isset($showData['theatre']) && is_string($showData['theatre'])) {
            $theatreKey = $showData['theatre'];
            if ($theatreKey == $this->theatre->key) {
                $showData['theatre'] = $this->theatre;
            } elseif (isset($this->theatresCache[$theatreKey])) {
                $showData['theatre'] = $this->theatresCache[$theatreKey];
            } else {
                /** @var Theatre $theatre */
                $theatre = R::dispense('theatre');
                $theatre->loadByKey($theatreKey);
                if ($theatre->id) {
                    $showData['theatre'] = $theatre;
                    $this->theatresCache[$theatreKey] = $theatre;
                }
            }
        }
    }

    /**
     * Populate scene by key.
     *
     * @param &array $showData Link to $showData array.
     */
    protected function populateScene(&$showData)
    {
        if (isset($showData['scene']) && is_string($showData['scene'])) {
            $sceneKey = $showData['scene'];
            if (isset($this->scenesCache[$sceneKey])) {
                $showData['scene'] = $this->scenesCache[$sceneKey];
            } else {
                /** @var Scene $scene */
                $scene = R::dispense('scene');
                $scene->loadByKey($sceneKey);
                if ($scene->id) {
                    $showData['scene'] = $scene;
                    $this->scenesCache[$sceneKey] = $scene;
                }
            }
        }
    }

    /**
     * Clear schedule for given month and year for current theatre.
     *
     * @param int $month
     * @param int $year
     * @param array $showsData
     */
    protected function clearSchedule($month, $year, $showsData = null)
    {
        $firstShow = current($showsData);
        $firstShowDate = $firstShow['date']->format('Y-m-d');

        R::exec(
            'delete from `show` where
               theatre_id = ?
               and `date` > ?
               and month (`date`) = ?
               and year(`date`) = ?',
            array($this->theatre->id, $firstShowDate, $month, $year));
    }

    /**
     * Setup show data.
     *
     * @param array $showData Show data.
     * @return \RedBean_OODBBean|Show
     */
    protected function setupShow($showData)
    {
        /** @var Show|\RedBean_OODBBean $show */
        $show = R::dispense('show');

        $play = $this->getPlay($showData);

        if ($play->id) {
            $showData['play'] = $play;
            if (!isset($showData['price']) || !$showData['price']) {
                $showData['price'] = $play->price;
            }
            if (!isset($showData['scene']) || !$showData['scene']) {
                $showData['scene_id'] = $play->scene_id;
            }
            if (!isset($showData['buy_tickets_link']) || !$showData['buy_tickets_link']) {
                $showData['buy_tickets_link'] = $play->buy_tickets_link;
            }

            $show->import($showData, Show::$allowedFields);
            $show->loadByHash($show->generateHash());
        }

        return $show;
    }

    /**
     * Get existing play or create new one.
     *
     * @param array $showData Show data.
     * @return \RedBean_OODBBean|Play
     */
    protected function getPlay($showData)
    {
        $titleTag = $showData['title'];
        /** @var Play|\RedBean_OODBBean $play */
        $play = R::dispense('play');

        $play->loadByTag($titleTag);
        if (!$play->id) {
            $this->createAndSaveNewPlay($play, $showData);
            return $play;
        }
        return $play;
    }

    /**
     * Create new play.
     *
     * @param \RedBean_OODBBean $play Play bean instance.
     * @param array $data Play data.
     */
    protected function createAndSaveNewPlay(\RedBean_OODBBean $play, $data)
    {
        $play->import($data, Play::$allowedFields);
        R::store($play);
        R::tag($play, array($data['title']));
    }
}