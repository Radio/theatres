<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Puppet extends Fetcher
{
    protected $theatreId = 'puppet';
    protected $source = 'http://puppet.kharkov.ua/afisha.html';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $cells = pq('li.afisha');

        foreach($cells as $cellDomElement) {
            $show = pq($cellDomElement);

            $leftColNode = $show->find('.date-afisha');
            $rightColNode = $show->find('.name-perform');
            $linkNode = $rightColNode->find('a');
            $dateLine = $leftColNode->text();
            $timeLine = $rightColNode->find('b')->html();
            $titleLine = $linkNode->text();
            $linkLine = $linkNode->attr('href');
            $imgLine = $leftColNode->find('img')->attr('src');
            $premiereLine = $rightColNode->text();

            $dates = $this->parseDates($dateLine, $timeLine);
            $title = $this->parseTitle($titleLine);
            $link = $this->parseLink($linkLine);
            $scene = $this->parseScene();
            $price = $this->parsePrice();
            $image = $this->parseImage($imgLine);
            $premiere = $this->parsePremiere($premiereLine);

            foreach ($dates as $date) {
                $play = array(
                    'theatre' => $this->theatreId,
                    'date' => $date,
                    'title' => $title,
                    'scene' => $scene,
                    'price' => $price,
                    'image' => $image,
                    'is_premier' => $premiere,
                    'link' => $link ? $link : $this->source,
                );

                $schedule[] = $play;
            }
        }
        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
    }

    /**
     * Date Source: 2 января 2015 пт
     * Time Source: 10:00
     *
     * @param string $dateHtml
     * @param string $timeHtml
     * @return \DateTime|null
     */
    private function parseDates($dateHtml, $timeHtml)
    {
        $dates = [];
        $hasDate = preg_match('/(\d+)\s*(.+?)\s+(\d+)/', trim($dateHtml), $match);

        if ($hasDate) {
            $d = $match[1];
            $monthTitle = $match[2];
            $y = $match[3];
            $m = Helpers\Date::mapMonthTitle($monthTitle, 'genitive');
            if (!$m) {
                $m = $this->month;
            }

            $timeHtml = preg_replace('/\s*<br\s*\/?>\s*/', '|', $timeHtml);
            $times = explode('|', $timeHtml);
            foreach ($times as $time) {
                if (trim($time)) {
                    $dates[] = new \DateTime("$y-$m-$d " . trim($time));
                }
            }
        }

        return $dates;
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
    private function parseImage($html)
    {
        $link = null;
        if ($html) {
            $link = Helpers\Html::fixUrl($html, $this->source);
        }

        return $link;
    }

    /**
     * Define if show is a premiere.
     *
     * @param $text
     * @return bool
     */
    private function parsePremiere($text)
    {
        return mb_strpos($text, 'премьера') !== false;
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
     * @return string
     */
    private function parsePrice()
    {
        return '30 грн (детский), 60 грн';
    }
}