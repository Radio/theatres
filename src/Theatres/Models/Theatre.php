<?php

namespace Theatres\Models;

use Theatres\Collections\Theatres;
use RedBean_Facade as R;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;

class Theatre extends \RedBean_SimpleModel
{
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
        R::exec('delete from play where theatre = ? and month (`date`) = ? and year(`date`) = ?',
            array($this->key, $month, $year));

        $plays = array();
        foreach ($schedule as $playData) {
            $play = R::dispense('play');
            $play->import($playData);
            $plays[] = $play;
        }
        R::storeAll($plays);
    }
}