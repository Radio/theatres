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

    public function __construct(Theatre $theatre)
    {
        $this->theatre = $theatre;
    }

    public function saveSchedule($showsData, $month, $year)
    {
        $this->clearSchedule($month, $year);

        $shows = array();
        foreach ($showsData as $showData) {
            $show = $this->setupShow($showData);
            if ($show) {
                $shows[] = $show;
            }
        }
        R::storeAll($shows);
    }

    protected function clearSchedule($month, $year)
    {
        R::exec('delete from `show` where theatre = ? and month (`date`) = ? and year(`date`) = ?',
            array($this->theatre->key, $month, $year));
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
            $showData['play'] = $play->key;
            $show->import($showData, array('theatre', 'play', 'scene', 'price', 'date'));
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
        $play->import($data, array('theatre', 'title', 'scene', 'link'));
        R::store($play);
        R::tag($play, array($data['title']));
    }
}