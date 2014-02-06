<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Pushkin extends Fetcher
{
    protected $theatreId = 'pushkin';
    //protected $source = 'http://rusdrama.kh.ua/tekushij-sezon/shedule?tmpl=component&print=1';
    protected $source = 'http://127.0.0.2/theatres/resources/pushkin.html';

    protected $pageContentsStart  = '<table';
    protected $pageContentsFinish = '</table>';

    protected function parseSchedule($html)
    {
        $schedule = array();

        $html = Helpers\Html::stripTags($html, '<table><tr><td><a><strong>');

        $matched = preg_match_all('/<tr.*?<\/tr>/is', $html, $lines);
        foreach ($lines[0] as $line) {
            $matched = preg_match_all('/<td.*?<\/td>/is', $line, $cells);
            $dateHtml = $cells[0][0];
            $timeHtml = $cells[0][1];
            $titleHtml = $cells[0][2];
            $date  = $this->parseDate($dateHtml, $timeHtml);
            $link  = $this->parseLink($titleHtml);
            $title = $this->parseTitle($titleHtml);
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

        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
    }

    /**
     * @param string $dateHtml
     * @param string $timeHtml
     * @return \DateTime|null
     */
    private function parseDate($dateHtml, $timeHtml)
    {
        $date = null;

        $text = Helpers\Html::stripTags($dateHtml);
        $hasDate = preg_match('/\d+/', $text, $match);
        $isDaily = mb_strpos($timeHtml, 'день');

        if ($hasDate) {
            $d = (int) $match[0];
            $m = (int) $this->month;
            $y = (int) $this->year;
            $h = $isDaily ? 12 : 19; // Время указано на сайте и одинаково для всех спектаклей
            $i = 0;
            $date = new \DateTime("$y-$m-$d $h:$i");
        }

        return $date;
    }

    /**
     * @param string $html
     * @return string
     */
    private function parseTitle($html)
    {
        $title = '';

        $hasTitle = preg_match('/<a.*?<\/a>/is', $html, $match);
        if ($hasTitle) {
            $titleHtml = $match[0];
            $title = Helpers\Html::fixSpaces(Helpers\Html::stripTags($titleHtml));
        }

        return $title;
    }

    /**
     * @param string $html
     * @return string
     */
    private function parseLink($html)
    {
        $link = null;

        $hasLink = preg_match('/href="([^"]+)"/', $html, $match);
        if ($hasLink) {
            $link = $match[1];
            $link = Helpers\Html::fixUrl($link, $this->source);
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