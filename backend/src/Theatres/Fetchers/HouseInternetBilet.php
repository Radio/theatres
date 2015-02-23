<?php

namespace Theatres\Fetchers;

use Theatres\Collections\Theatres;
use Theatres\Core\Fetcher;
use Theatres\Helpers;
use RedBean_Facade as R;

class HouseInternetBilet extends Fetcher
{
    protected $theatreId = 'house';
    protected $source = 'http://kharkov.internet-bilet.ua/event-rooms/item.html?room_id=71&name=Dom_Aktera2';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    /** @var \Theatres\Collections\Theatres  */
    protected static $theatres;

    public function __construct()
    {
        self::$theatres = new Theatres();
    }

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $rows = pq('.events-future li');

        foreach($rows as $rowDomElement) {
            $show = pq($rowDomElement);

            $titleLine = $show->find('a.underlined')->text();
            $dateLine = $show->find('span')->text();

            $theatre = $this->parseTheatre($titleLine);
            $title = $this->parseTitle($titleLine);
            $date = $this->parseDate($dateLine);

            if ($theatre) {
                $play = array(
                    'theatre' => $theatre->key,
                    'date' => $date,
                    'title' => $title,
                    'scene' => 'main',
                );

                $schedule[] = $play;
            }
        }
        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
    }

    /**
     * Source: 19 марта 2015 19:00
     *
     * @param string $html
     * @return \DateTime|null
     */
    private function parseDate($html)
    {
        $date = null;
        $hasDate = preg_match('/(\d+)\s*(.+?)\s+(\d+)\s+(\d+:\d+)/', trim($html), $match);

        if ($hasDate) {
            $d = $match[1];
            $monthTitle = $match[2];
            $y = $match[3];
            $time = $match[4];
            $m = Helpers\Date::mapMonthTitle($monthTitle, 'genitive');
            if (!$m) {
                $m = $this->month;
            }

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

    private function parseTheatre(&$title)
    {
        $title = trim(str_replace(['"','«','»'], ' ', $title));
        $title = preg_replace('/\s+/', ' ', $title);
        $title = str_replace(' .', '.', $title);
        foreach (self::$theatres as $theatre) {
            $theatreTitle = trim(str_replace(['"','«','»'], '', $theatre->title));
            if (mb_stripos($title, $theatreTitle, 0, 'UTF-8') === 0) {
                $title = trim(mb_substr($title, mb_strlen($theatreTitle, 'UTF-8'), null, 'UTF-8'), ',. ');

                return $theatre;
            }
        }

        return null;
    }
}