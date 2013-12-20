<?php

namespace Theatres\Controllers;

use Theatres\Fetchers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RedBean_Facade as R;

class Fetch
{
    protected $theatres = array(
        'theatre19',
        'shevchenko',
        'postscriptum',
        'pushkin',
    );

    public function index(Request $request, $theatre = null)
    {
        $contents = '';

        $month = $request->query->getInt('month', (int) date('n'));
        $year  = $request->query->getInt('year', (int) date('Y'));

        if ($theatre) {
            $contents = $this->fetchTheatre($theatre, $month, $year);
        } else {
            foreach ($this->getTheatres() as $theatre) {
                $contents .= $this->fetchTheatre($theatre, $month, $year) . '<br/>';
            }
        }

        return new Response($contents);
    }

    protected function fetchTheatre($theatre, $month, $year)
    {
        $fetcher = $this->getFetcher($theatre);
        $schedule = $fetcher->fetch($month, $year);
        $theatreId = $fetcher->getTheatreId();

        $this->storeSchedule($schedule, $theatreId, $month, $year);

        $contents = 'Fetched ' . count($schedule) . ' plays for ' . $theatre . ' for ' . $month . '.' . $year;

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
            case 'postscriptum':
                return new Fetchers\PostScriptum();
            case 'pushkin':
                return new Fetchers\Pushkin();
        }
        throw new \Exception();
    }

    protected function storeSchedule($schedule, $theatreId, $month, $year)
    {
        R::exec('delete from play where theatre = ? and month (`date`) = ? and year(`date`) = ?', array($theatreId, $month, $year));

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