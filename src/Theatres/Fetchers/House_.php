<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class House extends Fetcher
{
    protected $theatreId = 'house';
    //protected $source = 'http://domaktera.com.ua/afisha';
    protected $source = 'http://127.0.0.2/theatres/resources/house.html';

    protected $pageContentsStart  = '<table class="afisha">';
    protected $pageContentsFinish = '</table>';

    protected function parseSchedule($html)
    {
        $schedule = array();

        $html = str_replace("\t", '', $html);
        $html = str_replace("\n", '', $html);

        $matched = preg_match_all('/<td>.*?<\/td><(td)>/is', $html, $linesOnMainScene, PREG_SET_ORDER);
        $matched = preg_match_all('/<td>.*?<\/td><\/(tr)>/is', $html, $linesOnSmallScene, PREG_SET_ORDER);

        $lines = array_merge($linesOnMainScene, $linesOnSmallScene);

        var_dump($lines);

        foreach ($lines as $showData) {
            $cell = $showData[0];

            // В первой колонке спектакли на основной сцене, во второй — на малой.
            $scene = $this->parseScene($showData[1]);

            $matched = preg_match_all('/<div\s+class="show".*?<\/div>/is', $cell, $shows);
            foreach ($shows[0] as $line) {

                preg_match('/<time[^>]+datetime="([^"]+)"/is', $line, $match);
                $date = $match[1];

                preg_match('/<h1[^>]+itemprop="name"[^>]*><a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/is', $line, $match);

                $link  = $match[1];
                $title = $match[2];

                preg_match('/<p[^>]+itemprop="performer"[^>]*><a[^>]+href=".*?\/theaters\/([^"]+)"[^>]*>/is', $line, $match);

                $theatreSlug = $match[1];

                $date    = $this->parseDate($date);
                $title   = $this->parseTitle($title);
                $link    = $this->parseLink($link);
                $theatre = $this->parseTheatre($theatreSlug);

                $play = array(
                    'theatre' => $theatre,
                    'date' => $date,
                    'title' => $title,
                    'scene' => $scene,
                    'price' => null,
                    'link' => $link ? $link : $this->source,
                );

                $schedule[] = $play;
    /**/
            }
        }

        Helpers\Schedule::sortByDate($schedule);

        var_dump($schedule);

//        return $schedule;
        return array();
    }

    /**
     * Source: 2014-02-05T19:00
     *
     * @param string $html
     * @return \DateTime|null
     */
    private function parseDate($html)
    {
        $date = null;
        if ($html) {
            $date = new \DateTime($html);
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
     * Source: http://domaktera.com.ua/shows/no-vse-taki
     *
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
     * Source: td|tr
     * В первой колонке спектакли на основной сцене, во второй — на малой.
     *
     * @param string $html
     * @return string
     */
    private function parseScene($html)
    {
        return $html == 'td' ? 'main' : 'small';
    }

    private function parseTheatre($theatreSlug)
    {
        return $theatreSlug;
    }

}