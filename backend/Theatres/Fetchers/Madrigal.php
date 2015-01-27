<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Madrigal extends Fetcher
{
    protected $theatreId = 'madrigal';
    protected $source = 'http://madrigal.org.ua/afisha';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $cells = pq('#dAnnouncementBlock .cliRowStyle');

        foreach($cells as $cellDomElement) {
            $show = pq($cellDomElement);

            $leftColNode = $show->find('.photo');
            $middleColNode = $show->find('.content');
            $rightColNode = $show->find('.additional');
            $linkNode = $middleColNode->find('.itemHeader a');
            $dateLine = $leftColNode->text();
            $imgLine = $leftColNode->find('img')->attr('src');
            $timeLine = $rightColNode->find('.date')->text();
            $priceLine = $rightColNode->find('.Prise')->text();
            $titleLine = $linkNode->text();
            $linkLine = $linkNode->attr('href');

            $date = $this->parseDate($dateLine, $timeLine);
            $title = $this->parseTitle($titleLine);
            $link = $this->parseLink($linkLine);
            $scene = $this->parseScene();
            $price = $this->parsePrice($priceLine);
            $image = $this->parseImage($imgLine);

            $play = array(
                'theatre' => $this->theatreId,
                'date' => $date,
                'title' => $title,
                'scene' => $scene,
                'price' => $price,
                'image' => $image,
                'link' => $link ? $link : $this->source,
            );

            $schedule[] = $play;
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
    private function parseDate($dateHtml, $timeHtml)
    {
        $date = null;
        $hasDate = preg_match('/(\d+)\s+([^\s]+)/', trim($dateHtml), $match);

        if ($hasDate) {
            $d = $match[1];
            $monthTitle = $match[2];
            $y = $this->year;
            $m = Helpers\Date::mapMonthTitle($monthTitle, 'genitive');
            if (!$m) {
                $m = $this->month;
            }

            $time = trim($timeHtml);
            $date = new \DateTime("$y-$m-$d $time");
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
    private function parseImage($html)
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
     * Source: 50,45,40 грн.
     *
     * @param $priceHtml
     * @return string
     */
    private function parsePrice($priceHtml)
    {
        return Helpers\Price::normalizePrice($priceHtml);
    }
}