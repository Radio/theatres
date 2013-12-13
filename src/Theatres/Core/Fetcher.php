<?php

namespace Theatres\Core;

use Buzz\Browser;

abstract class Fetcher implements Fetcher_Interface
{
    protected $theatreId = null;
    protected $source = null;

    protected $pageContentsStart  = '';
    protected $pageContentsFinish = '';

    public function fetch()
    {
        $html = $this->getPageContents();
        $schedule = $this->parseSchedule($html);

        return $schedule;
    }

    abstract protected function parseSchedule($html);
    protected function getPageContents()
    {
        $browser = new Browser();
        $response = $browser->get($this->source);

        $html = (string) $response;

        $start  = strpos($html, $this->pageContentsStart);
        $finish = strpos($html, $this->pageContentsFinish, $start);

        $table = substr($html, $start, $finish - $start);

        $table = iconv('cp1251', 'utf-8//IGNORE', $table);

        return $table;
    }

    /**
     * @return string
     */
    public function getTheatreId()
    {
        return $this->theatreId;
    }
}