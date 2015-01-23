<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Shevchenko extends Fetcher
{
    protected $theatreId = 'shevchenko';
    protected $source = 'http://www.theatre-shevchenko.com.ua/repertuar/month.php?id=';

    protected $pageContentsStart  = '<ul class="primer-list">';
    protected $pageContentsFinish = '</ul>';

    /**
     * @return string
     */
    protected function getSource()
    {
        return $this->source . $this->month;
    }

    protected function convertCharset($html)
    {
        return iconv('cp1251', 'utf-8//IGNORE', $html);
    }

    protected function parseSchedule($html)
    {
        $schedule = array();

        $matched = preg_match_all('/<li.*?<\/li>/isu', $html, $lines);
        foreach ($lines[0] as $line) {
            preg_match('/<span\s+class="date">.*?<\/span>/isu', $line, $match);
            $dateHtml = $match[0];

            preg_match('/<h3.*?<\/h3>/isu', $line, $match);
            $titleHtml = $match[0];

            preg_match('/<em.*?<\/em>/isu', $line, $match);
            $detailsHtml = $match[0];

            $date  = $this->parseDate($dateHtml);
            $title = $this->parseTitle($titleHtml);
            $link  = $this->parseLink($titleHtml);
            $scene = $this->parseScene($detailsHtml);
            $price = $this->parsePrice($detailsHtml);

            $play = array(
                'theatre' => $this->theatreId,
                'date' => $date,
                'title' => $title,
                'scene' => $scene,
                'price' => $price,
                'link' => $link ? $link : $this->source,
            );
            $schedule[] = $play;
        }

        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
    }


    /**
     * Source: <span class="date"><strong>1</strong> Неділя  18.00</span>
     *
     * @param string $html
     * @return \DateTime|null
     */
    private function parseDate($html)
    {
        $date = null;

        $text = Helpers\Html::stripTags($html);
        $hasDate = preg_match('/(\d+).+(\d\d)[\.\:](\d\d)/u', $text, $match);

        if ($hasDate) {
            $d = (int) $match[1];
            $m = (int) $this->month;
            $y = (int) $this->year;
            $h = (int) $match[2];
            $i = (int) $match[3];
            $date = new \DateTime("$y-$m-$d $h:$i");
        }

        return $date;
    }

    /**
     * Source: <h3><a href="/performance/index.php?perf=53">Королева краси</a></h3>
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
     * Source: <h3><a href="/performance/index.php?perf=53">Королева краси</a></h3>
     *
     * @param string $html
     * @return string
     */
    private function parseLink($html)
    {
        $link = null;

        $hasLink = preg_match('/href="([^"]+)"/u', $html, $match);
        if ($hasLink) {
            $link = $match[1];
            $link = Helpers\Html::fixUrl($link, $this->source);
        }

        return $link;
    }

    /**
     * Source: <em>Велика сцена  <span class="prices">квиток коштує 20 - 50грн</span></em>
     *
     * @param string $html
     * @return string
     */
    private function parseScene($html)
    {
        $scene = '';

        $hasScene = preg_match('/(Мала сцена|Велика сцена|Експериментальна сцена)/u', $html, $match);
        if ($hasScene) {
            switch($match[0]) {
                case 'Мала сцена':
                    $scene = 'small'; break;
                case 'Експериментальна сцена':
                    $scene = 'exp'; break;
                case 'Велика сцена':
                default:
                    $scene = 'big'; break;
            }
        }

        return $scene;
    }

    /**
     * Source: <em>Велика сцена  <span class="prices">квиток коштує 20 - 50грн</span></em>
     *
     * @param string $html
     * @return string
     */
    private function parsePrice($html)
    {
        $price = null;

        $hasLink = preg_match('/квиток\s+коштує\s+([^<]+)/u', $html, $match);
        if ($hasLink) {
            $price = $match[1];
        }
        $price = preg_replace('/([^ ])грн/isu', '$1 грн', $price);
        $price = preg_replace('/\s[-–−]\s/isu', '–', $price);

        return $price;
    }
}