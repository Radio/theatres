<?php

namespace Theatres\Fetchers;

use Theatres\Collections\Theatres;
use Theatres\Core\Fetcher;
use Theatres\Helpers;

class House extends Fetcher
{
    protected $theatreId = 'house';
    //protected $source = 'http://domaktera.com.ua/afisha';
    protected $source = 'http://127.0.0.2/theatres/resources/house.html';

    protected $pageContentsStart  = '<table class="afisha">';
    protected $pageContentsFinish = '</table>';

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

        $cells = pq('td:not(.day)');

        foreach($cells as $cellDomElement) {
            $cell = pq($cellDomElement);
            $shows = $cell->find('.show');

            $scene = $cell->is(':last-child') ? 'small' : 'main';

            foreach ($shows as $showDomElement) {
                $show = pq($showDomElement);

                $dateLine  = $show->find('time')->attr('datetime');

                $titleLink = $show->find('[itemprop="name"] a');
                $titleLine = $titleLink->text();
                $linkLine = $titleLink->attr('href');

                $theatre = $this->parseTheatre($show);

                $date    = $this->parseDate($dateLine);
                $title   = $this->parseTitle($titleLine);
                $link    = $this->parseLink($linkLine);

                if ($theatre) {

                    $play = array(
                        'theatre' => $theatre,
                        'date' => $date,
                        'title' => $title,
                        'scene' => $scene,
                        'price' => null,
                        'link' => $link ? $link : $this->source,
                    );

                    $schedule[] = $play;
                }
            }
        }
        Helpers\Schedule::sortByDate($schedule);

        var_dump($schedule);

        return $schedule;
//        return array();
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

    private function parseTheatre(\phpQueryObject $show)
    {
        $link = $show->find('[itemprop="performer"] a')->attr('href');
        $title = $show->find('[itemprop="performer"] a')->text();

        $slug = substr($link, strrpos($link, '/') + 1);
        $theatre = self::$theatres->findWith('house_slug', $slug);

        return $theatre ? $theatre->key : null;
    }

}