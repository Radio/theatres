<?php

namespace Theatres\Models;

use RedBean_Facade as R;
use Theatres\Exceptions\MoreThanOneBeanFound;

/**
 * Class Schedule
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

    private function clearSchedule($month, $year)
    {
        if ($this->theatre->key != 'house') {
            R::exec('delete from `show` where theatre = ? and month (`date`) = ? and year(`date`) = ?',
                array($this->theatre->key, $month, $year));
        } else {
            R::exec('
            delete from `show` where
                theatre in (select `key` from theatre where house_slug is not null and house_slug != "")
                and month (`date`) = ?
                and year(`date`) = ?',
                array($month, $year));
        }
    }

    protected function setupShow($showData)
    {
        /** @var Show|\RedBean_OODBBean $show */
        $show = R::dispense('show');

        $titleTag = $showData['title'];
        /** @var Play|\RedBean_OODBBean $play */
        $play = R::dispense('play');
        try {
            $play->loadByTag($titleTag);
            if (!$play->id) {
                $this->createAndSaveNewPlay($play, $showData);
            }
            if ($play->id) {
                $showData['play'] = $play->key;
                $show->import($showData, array('theatre', 'play', 'scene', 'price', 'date'));
            }
        } catch (MoreThanOneBeanFound $e) {
            throw $e;
        }

        return $show;
    }

    /**
     * @param \RedBean_OODBBean $play
     * @param $data
     */
    protected function createAndSaveNewPlay(\RedBean_OODBBean $play, $data)
    {
        $play->import($data, array('theatre', 'title', 'scene', 'link'));
        R::store($play);
        R::tag($play, array($data['title']));
    }
}