<?php

namespace Theatres\Fetchers;

use Theatres\Collections\Theatres;
use Theatres\Core\Fetcher;
use Theatres\Helpers;
use RedBean_Facade as R;

class HouseTvojKharkov extends Fetcher
{
    protected $theatreId = 'house';
    protected $source = 'http://tvoj.kharkov.ua/help/Theatre/Dom-Aktera/';

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

        \phpQuery::newDocumentHTML(Helpers\Html::cp1251ToUtf8($html));

        $rows = pq('#tab1 tbody tr');

        foreach($rows as $rowDomElement) {
            $show = pq($rowDomElement);

            $cells = $show->find('td');

            $dateLine = ($cells->get(0)->nodeValue);
            $titleLine = ($cells->get(1)->nodeValue);
            $theatreLine = ($cells->get(2)->nodeValue);

            $date = $this->parseDate($dateLine);
            $title = $this->parseTitle($titleLine);
            $theatre = $this->parseTheatre($theatreLine);

            $play = array(
                'theatre' => $theatre,
                'date' => $date,
                'title' => $title,
                'scene' => 'main',
            );

            $schedule[] = $play;
        }
        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
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
        if ((int) $html) {
            $y = $this->year;
            $m = $this->month;
            $d = str_pad((int) $html, 2, '0', STR_PAD_LEFT);
            $time = '19:00:00';
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

    private function parseTheatre($title)
    {
        $theatre = self::$theatres->findWith('title', $title);

        if ($theatre) {
            return $theatre->key;
        } else {
            $theatre = $this->createTheatre([
                'title' => $title,
                'key' => Helpers\Models::generateKey($title),
                'house_slug' => $title,
            ]);
            if ($theatre) {
                self::$theatres->addItem($theatre);
            }
        }

        return $theatre ? $theatre->key : null;
    }

    private function createTheatre($data)
    {
        $theatre = R::dispense('theatre');
        $theatre->import($data);
        R::store($theatre);

        return $theatre;
    }
}