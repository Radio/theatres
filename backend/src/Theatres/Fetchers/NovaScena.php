<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class NovaScena extends Fetcher
{
    protected $theatreId = 'novascena';
    protected $source = 'http://novascena.org/?page=playbills';

    protected $pageContentsStart  = '<div id="main-content">';
    protected $pageContentsFinish = '<div id="footer">';

    /**
     * Don't add $pageContentsFinish length.
     *
     * @param $html
     * @param int $start
     * @return int
     */
    protected function getFinishPosition($html, $start = 0)
    {
        return mb_stripos($html, $this->pageContentsFinish, $start);
    }

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $cells = pq('.slider>ul>li');

        foreach($cells as $cellDomElement) {
            $show = pq($cellDomElement);

            $dateLine  = $show->find('.date')->text();
            $titleLine = $show->find('img')->attr('alt');
            $link = $show->find('.links-list a')->get(0);
            $linkLine = pq($link)->attr('href');

            $date    = $this->parseDate($dateLine);
            $title   = $this->parseTitle($titleLine);
            $link    = $this->parseLink($linkLine);
            $scene   = $this->parseScene();


            $play = array(
                'theatre' => $this->theatreId,
                'date' => $date,
                'title' => $title,
                'scene' => $scene,
                'price' => null,
                'link' => $link ? $link : $this->source,
            );

            $schedule[] = $play;
        }
        Helpers\Schedule::sortByDate($schedule);

        //var_dump($schedule);

        return $schedule;
    }

    /**
     * Source: 19:00 / 17 янв 2015
     *
     * @param $dateHtml
     * @return \DateTime|null
     */
    private function parseDate($dateHtml)
    {
        $date = null;

        $hasDate = preg_match('/(\d\d):(\d\d)\s*\/\s*(\d+)\s+(.+?)\s*(\d+)/', $dateHtml, $match);

        if ($hasDate) {
            $h = $match[1];
            $i = $match[2];
            $d = $match[3];
            $monthTitle = $match[4];
            $m = Helpers\Date::mapMonthShortTitle($monthTitle);
            if (!$m) {
                $m = $this->month;
            }
            $y = $match[5];

            $date = new \DateTime("$y-$m-$d $h:$i");
        }

        return $date;
    }

    /**
     * Source: Но все-таки
     *
     * @param string $html
     * @return string
     */
    private function parseTitle($html)
    {
        $title = Helpers\Html::fixSpaces(Helpers\Html::stripTags($html));

        return $title;
    }

    /**
     * @param string $html
     * @return string
     */
    private function parseLink($html)
    {
        $link = null;

        if ($html) {
            $link = Helpers\Html::fixUrl($html, $this->source);
        }

        return $link;
    }

    /**
     * @param string $html
     * @return string
     */
    private function parseScene($html = null)
    {
        return 'main';
    }
}