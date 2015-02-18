<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class BeautifulFlowers extends Fetcher
{
    protected $theatreId = 'beautifulflowers';
    protected $source = 'http://gobananas.com.ua';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    protected $linkTitlesMap = [
        'http://gobananas.com.ua/show/fat' => 'Жир',
        'http://gobananas.com.ua/show/red' => 'Красный',
        'http://gobananas.com.ua/show/rat' => 'Крыса',
    ];

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $cells = pq('.events-list .event');

        foreach($cells as $cellDomElement) {
            $show = pq($cellDomElement);

            $link = pq($show->find('a')->get(0));
            $linkLine = $link->attr('href');
            $dateLine = $show->find('.event-date')->text();

            $date = $this->parseDate($dateLine);
            $title = $this->parseTitle($linkLine);
            $link = $this->parseLink($linkLine);
            $scene = $this->parseScene();

            if ($title) {
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

        $hasDate = preg_match('/(\d+)\s*(.+?)\s+в\s+(\d\d):(\d\d)/', trim($dateHtml), $match);

        if ($hasDate) {
            $d = $match[1];
            $monthTitle = $match[2];
            $h = $match[3];
            $i = $match[4];
            $m = Helpers\Date::mapMonthTitle($monthTitle, 'genitive');
            if (!$m) {
                $m = $this->month;
            }
            $y = $this->year;

            $date = new \DateTime("$y-$m-$d $h:$i");
        }

        return $date;
    }

    /**
     * Source: Но все-таки
     *
     * @param string $link
     * @return string
     */
    private function parseTitle($link)
    {
        $title = isset($this->linkTitlesMap[$link]) ? $this->linkTitlesMap[$link] : null;

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