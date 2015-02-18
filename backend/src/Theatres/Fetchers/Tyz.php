<?php

namespace Theatres\Fetchers;

use Theatres\Core\Fetcher;
use Theatres\Helpers;

class Tyz extends Fetcher
{
    protected $theatreId = 'tyz';
    protected $source = 'http://tyz.kharkov.ua/';

    protected $pageContentsStart  = '<body';
    protected $pageContentsFinish = '</body>';

    protected function parseSchedule($html)
    {
        $schedule = array();

        \phpQuery::newDocumentHTML($html);

        $cells = pq('.affisha li');

        foreach($cells as $cellDomElement) {
            $show = pq($cellDomElement);

            $linkNode = $show->find('strong a');
            $dateLine = $show->find('time')->attr('datetime');
            $titleLine = $linkNode->text();
            $linkLine = $linkNode->attr('href');
            $premiereLine = $show->text();

            $date = $this->parseDate($dateLine);
            $title = $this->parseTitle($titleLine);
            $link = $this->parseLink($linkLine);
            $scene = $this->parseScene();
            $premiere = $this->parsePremiere($premiereLine);

            $play = array(
                'theatre' => $this->theatreId,
                'date' => $date,
                'title' => $title,
                'scene' => $scene,
                'is_premiere' => $premiere,
                'link' => $link ? $link : $this->source,
            );

            $schedule[] = $play;
        }
        Helpers\Schedule::sortByDate($schedule);

        return $schedule;
    }

    /**
     * Source: 2015-01-02 13:30
     *
     * @param string $dateHtml
     * @return \DateTime|null
     */
    private function parseDate($dateHtml)
    {
        $date = null;

        if (trim($dateHtml)) {
            $date = new \DateTime(trim($dateHtml));
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
        $title = preg_replace('/^[«"](.*)[»"]$/u', '$1', $title);

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
    private function parseImage($html)
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
     * @param $text
     * @return string
     */
    private function parsePremiere($text)
    {
        return mb_stripos($text, 'премьера', null, 'utf-8') !== false;
    }

}