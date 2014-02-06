<?php

namespace Theatres\Models;

use Theatres\Collections\Theatres;
use RedBean_Facade as R;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;

/**
 * Class Theatre
 *
 * @property $id
 * @property $title
 * @property $abbr
 * @property $link
 * @property $key
 * @property $fetcher
 * @property $house_slug
 *
 * @package Theatres\Models
 */
class Theatre extends \RedBean_SimpleModel
{
    const HOUSE_ABBR = 'ДА';
    const HOUSE_TITLE_ADDITION = 'в Доме Актера';

    public function loadByKey($key)
    {
        $theatres = new Theatres();
        $theatres->setConditions('`key` = ?', array($key));

        $first = $theatres->getFirst();
        if ($first) {
            $this->bean->importFrom($first);
        }
    }

    /**
     * @throws \Theatres\Core\Exceptions\Fetchers_UndefinedFetcher
     * @return \Theatres\Core\Fetcher_Interface
     */
    public function getFetcher()
    {
        $fetcherClassName = '\\Theatres\\Fetchers\\' . $this->fetcher;

        if (class_exists($fetcherClassName)) {
            return new $fetcherClassName;
        } else {
            throw new Fetchers_UndefinedFetcher("Fetcher '$fetcherClassName' is not defined.");
        }
    }

    public function storeSchedule($schedule, $month, $year)
    {
        $this->clearSchedule($month, $year);

        $plays = array();
        foreach ($schedule as $playData) {
            $play = R::dispense('play');
            $play->import($playData);
            $plays[] = $play;
        }
        R::storeAll($plays);
    }

    private function clearSchedule($month, $year)
    {
        if ($this->key != 'house') {
            R::exec('delete from play where theatre = ? and month (`date`) = ? and year(`date`) = ?',
                array($this->key, $month, $year));
        } else {
            R::exec('delete from play where theatre in (select `key` from theatre where house_slug is not null and house_slug != "") and month (`date`) = ? and year(`date`) = ?',
                array($month, $year));
        }
    }

    public function isInHouse()
    {
        return (bool) $this->house_slug;
    }

    public function getAbbr($full = false)
    {
        return $this->abbr . ($full && $this->isInHouse() ? ' (' . self::HOUSE_ABBR . ')' : '');
    }

    public function getTitle($full = false)
    {
        return $this->title . ($full && $this->isInHouse() ? ' (' . self::HOUSE_TITLE_ADDITION . ')' : '');
    }
}