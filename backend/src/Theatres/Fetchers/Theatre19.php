<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Theatre19 extends Fetcher
{
    protected $theatreId = 'theatre19';
    protected $source = 'http://www.theatre19.com.ua/scena.php';
    //protected $source = 'http://www.theatre19.com.ua/scena.php?ddlMonth=119';

    protected $pageContentsStart  = '<table width="54';
    protected $pageContentsFinish = '</table>';

    protected function convertCharset($html)
    {
        return iconv('cp1251', 'utf-8//IGNORE', $html);
    }

    protected function parseSchedule($html)
    {
        $schedule = array();

        $matched = preg_match_all('/<tr.*?<\/tr>/is', $html, $lines);
        foreach ($lines[0] as $line) {
            $matched = preg_match_all('/<td.*?<\/td>/is', $line, $cells);
            $dateHtml = $cells[0][0];
            $titleHtml = $cells[0][1];
            $date  = $this->parseDate($dateHtml);
            $title = $this->parseTitle($titleHtml);
            $scene = $this->parseScene($titleHtml);
            $play = array(
                'theatre' => $this->theatreId,
                'date' => $date,
                'title' => $title,
                'scene' => $scene,
                'price' => null,
                'link' => $this->source, // На сайте нет отдельной страницы для каждого спектакля.
            );
            $schedule[] = $play;
        }

        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
    }

    /**
     * @param string $html
     * @return \DateTime|null
     */
    private function parseDate($html)
    {
        $date = null;

        $text = Helpers\Html::stripTags($html);
        $hasDate = preg_match('/\d+/', $text, $match);

        if ($hasDate) {
            $d = (int) $match[0];
            $m = (int) $this->month;
            $y = (int) $this->year;
            $h = 19; // Время указано на сайте и одинаково для всех спектаклей
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

        $hasTitle = preg_match('/<strong.*?<\/strong>/is', $html, $match);
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
    private function parseScene($html)
    {
        $scene = '';

        $hasScene = preg_match('/(на основной сцене|на малой сцене)/', $html, $match);
        if ($hasScene) {
            switch($match[0]) {
                case 'на малой сцене':
                    $scene = 'small'; break;
                case 'на основной сцене':
                default:
                    $scene = 'main'; break;
            }
        }

        return $scene;
    }
}