<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Operetta extends Fetcher
{
    protected $theatreId = 'operetta';
    protected $source = 'http://www.operetta.kharkiv.ua/calendar-field-perfomance-date/month/';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    /**
     * @return string
     */
    protected function getSource()
    {
        return $this->source . $this->year . '-' . $this->month;
    }

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $dateCells = pq('.calendar-calendar td.single-day');

        foreach($dateCells as $dateDomElement) {
            $dateCellNode = pq($dateDomElement);

            $items = $dateCellNode->find('.item');
            if (!$items->length) {
                continue;
            }

            $dateLine = $dateCellNode->attr('data-date');

            foreach($items as $showDomElement) {
                $show = pq($showDomElement);

                $linkNode = $show->find('a');

                $timeLine = $show->find('.date-display-single')->text();
                $titleLine = $linkNode->text();
                $linkLine = $linkNode->attr('href');

                $date = $this->parseDate($dateLine, $timeLine);

                if ($date) {
                    $title = $this->parseTitle($titleLine);
                    $link = $this->parseLink($linkLine);
                    $scene = $this->parseScene();
                    $price = $this->parsePrice($date);
                    $premiere = $this->parsePremiere($titleLine);

                    $play = array(
                        'theatre' => $this->theatreId,
                        'date' => $date,
                        'title' => $title,
                        'scene' => $scene,
                        'price' => $price,
                        'is_premiere' => $premiere,
                        'link' => $link ? $link : $this->source,
                    );

                    $schedule[] = $play;
                }
            }
        }
        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
    }

    /**
     * Date Source: 2014-12-31
     * Time Source: 11:00
     *
     * @param string $dateHtml
     * @param string $timeHtml
     * @return \DateTime|null
     */
    private function parseDate($dateHtml, $timeHtml)
    {
        $date = null;
        $day = trim($dateHtml);
        $time = trim($timeHtml);
        if ($day && $time) {
            $date = new \DateTime("$day $time");
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

    /**
     * Define if show is a premiere.
     *
     * @param $text
     * @return bool
     */
    private function parsePremiere($text)
    {
        return mb_stripos($text, 'премьера', null, 'utf-8') !== false;
    }

    /**
     * According to: http://www.operetta.kharkiv.ua/bilety
     *
     * @param \DateTime $date
     * @return string
     */
    private function parsePrice($date)
    {
        if ($date->format('H') < 16) {
            $price = '30 грн';
        } else {
            $price = '20 - 60 грн';
        }

        return Helpers\Price::normalizePrice($price);
    }
}