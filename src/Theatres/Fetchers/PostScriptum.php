<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

/**
 * @todo: Parse plays urls
 *
 * Class PostScriptum
 * @package Theatres\Fetchers
 */
class PostScriptum extends Fetcher
{
    protected $theatreId = 'postscriptum';
    protected $source = 'http://ps-teatr.com.ua';
//    protected $source = 'http://127.0.0.2/theatres/resources/postscriptum.html';

    protected $pageContentsStart  = '<div class="post">';
    protected $pageContentsFinish = '</div> <!-- Closes Post -->';

    protected function parseSchedule($html)
    {
        $schedule = array();

        $html = Helpers\Html::stripTags($html, '<p><strong>');

        // 22.01 (ср. 19:00) &#8211; &#8220;СОЙЧИНЕ КРИЛО&#8221;
        // 22.01 (ср. 19:00) – “СОЙЧИНЕ КРИЛО”
        $matched = preg_match_all('/(\d\d\.\d\d)\s+\([^\s]+\s+(\d\d:\d\d)\)\s+\&\#8211;\s+\&\#8220;(.*?)\&\#8221;/is', $html, $lines, PREG_SET_ORDER);
        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            $priceHtmlStart  = mb_strpos($html, $line[0]) + mb_strlen($line[0]);
            $priceHtmlFinish = isset($lines[$i+1]) ? mb_strpos($html, $lines[$i+1][0]) : mb_strlen($html);

            $priceHtml = mb_substr($html, $priceHtmlStart, $priceHtmlFinish - $priceHtmlStart);

            $date  = $this->parseDate($line[1], $line[2]);
            $title = $this->parseTitle($line[3]);
            $scene = $this->parseScene();
            $link  = $this->parseLink('');
            $price = $this->parsePrice($priceHtml);

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
     * @param string $datePart
     * @param string $timePart
     * @return \DateTime|null
     */
    private function parseDate($datePart, $timePart)
    {
        $date = null;

        $year = date('Y') + 1;
        list($day, $month) = explode('.', $datePart);
        list($hour, $minute) = explode(':', $timePart);

        $date = new \DateTime(date('Y-m-d H:i:s', mktime($hour, $minute, 0, $month, $day, $year)));

        return $date;
    }

    /**
     * Source: Королева краси
     *
     * @param string $html
     * @return string
     */
    private function parseTitle($html)
    {
        return html_entity_decode($html, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Source: <h3><a href="/performance/index.php?perf=53">Королева краси</a></h3>
     *
     * @param string $html
     * @return string
     */
    private function parseLink($html = null)
    {
        return null;
    }

    /**
     * Source: <em>Велика сцена  <span class="prices">квиток коштує 20 - 50грн</span></em>
     *
     * @param string $html
     * @return string
     */
    private function parseScene($html = null)
    {
        return 'main';
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

        $prices = array();
        $matched = preg_match_all('/(\d+)\s*грн/is', $html, $matches);
        foreach ($matches[1] as $priceString) {
            $prices[] = (int) $priceString;
        }

        $numberOfPrices = count($prices);
        if (!$numberOfPrices) {
            $price = null;
        } elseif ($numberOfPrices == 1) {
            $price = $prices[0] . ' грн';
        } else {
            $price = min($prices) . '–' . max($prices) . ' грн';
        }

        return $price;
    }
}