<?php

namespace Theatres\Controllers;

use Theatres\Fetchers;
use Symfony\Component\HttpFoundation\Response;
use RedBean_Facade as R;

class Fetch
{
    protected $theatres = array(
        'theatre19',
        'shevchenko',
//        'postscriptum'
    );

    public function index($theatre = null)
    {
        $contents = '';

        if ($theatre) {
            $contents = $this->fetchTheatre($theatre);
        } else {
            foreach ($this->getTheatres() as $theatre) {
                $contents .= $this->fetchTheatre($theatre);
            }
        }

        return new Response($contents);
    }

    protected function fetchTheatre($theatre)
    {
        $fetcher = $this->getFetcher($theatre);
        $schedule = $fetcher->fetch();
        $theatreId = $fetcher->getTheatreId();

        $this->storeSchedule($schedule, $theatreId);

        $contents = print_r($schedule, true);

        return $contents;
    }

    /**
     * @param $theatre
     * @return \Theatres\Core\Fetcher
     * @throws \Exception
     */
    protected function getFetcher($theatre)
    {
        switch ($theatre) {
            case 'theatre19':
                return new Fetchers\Theatre19();
            case 'shevchenko':
                return new Fetchers\Shevchenko();
        }
        throw new \Exception();
    }

    protected function storeSchedule($schedule, $theatreId)
    {
        R::exec('delete from play where theatre = ?', array($theatreId));

        $plays = array();
        foreach ($schedule as $playData) {
            $play = R::dispense('play');
            $play->import($playData);
            $plays[] = $play;
        }
        R::storeAll($plays);
    }

    /**
     * @return array
     */
    public function getTheatres()
    {
        return $this->theatres;
    }
}