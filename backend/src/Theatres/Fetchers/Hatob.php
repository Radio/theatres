<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Hatob extends Fetcher
{
    protected $theatreId = 'hatob';
    protected $source = 'http://www.hatob.com.ua/index.php?option=com_jevents&task=month.calendar&Itemid=6&year=%d&month=%d';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    /**
     * @return string
     */
    protected function getSource()
    {
        return sprintf($this->source, $this->year, $this->month);
    }

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $dateCells = pq('.cal_table td.cal_td_daysnoevents');

        // todo: продолжить

        foreach($dateCells as $dateDomElement) {
            $dateCellNode = pq($dateDomElement);

            $items = $dateCellNode->find('.eventstyle');
            if (!$items->length) {
                continue;
            }

            $day = $dateCellNode->find('.cal_daylink')->text();

            foreach($items as $showDomElement) {
                $show = pq($showDomElement);

                $linkNode = $show->find('a');

                $timeLine = $linkNode->text();
                $titleLine = $show->attr('onmouseover');
                $linkLine = $linkNode->attr('href');

                $date = $this->parseDate($day, $timeLine);

                if ($date) {
                    $title = $this->parseTitle($titleLine);
                    $link = $this->parseLink($linkLine);
                    $scene = $this->parseScene();

                    $play = array(
                        'theatre' => $this->theatreId,
                        'date' => $date,
                        'title' => $title,
                        'scene' => $scene,
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
     * @param string $day
     * @param string $timeHtml
     * @return \DateTime|null
     */
    private function parseDate($day, $timeHtml)
    {
        $date = null;
        $y = $this->year;
        $m = $this->month;
        $d = (int) trim($day);
        $hasTime = preg_match('/^(\d\d:\d\d)\s/', trim(Helpers\Html::stripTags($timeHtml)), $match);

        if ($day && $hasTime) {
            $date = new \DateTime("$y-$m-$d {$match[1]}");
        }

        return $date;
    }

    /**
     * Source: return overlib('<table style="border:0px;height:100%"><tr><td nowrap=&quot;nowrap&quot;>\
     *  Пятница, 16. Января 2015<br />18:30 - 20:30<br /><span style="font-weight:bold">Однодневное событие\
     *  </span><hr /><small>Нажмите чтобы посмотреть событие</small></td></tr></table>', CAPTION, 'ЖИЗЕЛЬ'\
     *  , LEFT, FGCOLOR, '#FFFFE2', BGCOLOR, '#660000', CAPCOLOR, '#FFFFFF', AUTOSTATUSCAP)
     *
     * @param string $html
     * @return string
     */
    private function parseTitle($html)
    {
        $title = null;
        $hasTitle = preg_match('/CAPTION,\s*\'(.*?[^\\\])\',/', $html, $match);
        if ($hasTitle) {
            $title = $match[1];
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