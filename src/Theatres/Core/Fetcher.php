<?php

namespace Theatres\Core;

use Buzz\Browser;

abstract class Fetcher implements Fetcher_Interface
{
    protected $theatreId = null;
    protected $source = null;

    protected $pageContentsStart  = '';
    protected $pageContentsFinish = '';

    protected $month;
    protected $year;

    public function fetch($month, $year)
    {
        $this->month = $month;
        $this->year  = $year;

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

        $start  = $this->getStartPosition($html);
        $finish = $this->getFinishPosition($html, $start);

        $table = substr($html, $start, $finish - $start);

        $table = $this->convertCharset($table);

        return $table;
    }

    protected function getStartPosition($html)
    {
        return stripos($html, $this->pageContentsStart);
    }

    protected function getFinishPosition($html, $start = 0)
    {
        return stripos($html, $this->pageContentsFinish, $start) + strlen($this->pageContentsFinish);
    }

    protected function convertCharset($html)
    {
        return $html;
    }

    /**
     * @return string
     */
    public function getTheatreId()
    {
        return $this->theatreId;
    }
}