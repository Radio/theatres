<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Moget extends Fetcher
{
    protected $theatreId = 'moget';
    protected $source = 'http://moget.com.ua/afisha';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $cells = pq('.region.region-bottom .views-row');

        foreach($cells as $cellDomElement) {
            $show = pq($cellDomElement);

            $dateLines = $show->find('.date-display-single');
            foreach ($dateLines as $dateLine) {
                $dateLine  = pq($dateLine)->attr('content');
                $link = $show->find('.views-field-title a');
                $titleLine = $link->text();
                $linkLine = $link->attr('href');

                $date = $this->parseDate($dateLine);
                $title = $this->parseTitle($titleLine);
                $link = $this->parseLink($linkLine);
                $scene = $this->parseScene();

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
        if ($dateHtml) {
            $date = new \DateTime($dateHtml);
            $date->setTime(19, 00);
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