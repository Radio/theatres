<?php

namespace Theatres\Models;

class Play extends \RedBean_SimpleModel
{
    private static $scenes = array(
        'main'  => 'главная сцена',
        'small' => 'малая сцена',
        'big'   => 'большая сцена',
        'exp'   => 'экспериментальная сцена',
    );

    private static $theatres = array(
        'theatre19' => array(
            'title' => 'Театр 19',
            'abbr' => 'Т19',
            'link' => 'http://www.theatre19.com.ua'
        ),
        'shevchenko' => array(
            'title' => 'Театр им. Шевченко',
            'abbr' => 'ТШ',
            'link' => 'http://www.theatre-shevchenko.com.ua'
        ),
    );

    public function update()
    {
        $this->hash = $this->generateHash();
    }

    protected function generateHash()
    {
        $line = sprintf('%s-%s-%s-%s-%s',
            $this->theatre,
            $this->date,
            $this->title,
            $this->scene,
            $this->link
        );
        return md5($line);
    }

    public function getDate()
    {
        return $this->getDateTime()->setTime(0, 0);
    }

    public function getDateTime()
    {
        return new \DateTime($this->date);
    }

    public function getSceneTitle()
    {
        return isset(self::$scenes[$this->scene]) ? self::$scenes[$this->scene] : $this->scene;
    }

    public function getTheatre()
    {
        return isset(self::$theatres[$this->theatre]) ? self::$theatres[$this->theatre] : null;
    }

    public function getTheatreTitle()
    {
        return ($theatre = $this->getTheatre()) ? $theatre['title'] : '';
    }

    public function getTheatreAbbr()
    {
        return ($theatre = $this->getTheatre()) ? $theatre['abbr'] : '';
    }

    public function getTheatreLink()
    {
        return ($theatre = $this->getTheatre()) ? $theatre['link'] : '';
    }
}