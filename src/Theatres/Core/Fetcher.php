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
        $response = $browser->get($this->getSource());

        $html = $response->getContent();

        $htmlInUtf8 = $this->convertCharset($html);

        $start  = $this->getStartPosition($htmlInUtf8);
        $finish = $this->getFinishPosition($htmlInUtf8, $start);

        $table = mb_substr($htmlInUtf8, $start, $finish - $start);

        return $table;
    }

    protected function getStartPosition($html)
    {
        return mb_stripos($html, $this->pageContentsStart);
    }

    protected function getFinishPosition($html, $start = 0)
    {
        return mb_stripos($html, $this->pageContentsFinish, $start) + mb_strlen($this->pageContentsFinish);
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

    /**
     * @return string
     */
    protected function getSource()
    {
        return $this->source;
    }
}