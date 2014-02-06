<?php

namespace Theatres\Models;

use Theatres\Collections\Theatres;
use Theatres\Collections\Scenes;

class Play extends \RedBean_SimpleModel
{
    /** @var \Theatres\Collections\Scenes */
    private static $scenes;

    /** @var \Theatres\Collections\Theatres */
    private static $theatres;

    private static function loadTheatres()
    {
        if (is_null(self::$theatres)) {
            self::$theatres = new Theatres();
        }
    }

    private static function loadScenes()
    {
        if (is_null(self::$scenes)) {
            self::$scenes = new Scenes();
        }
    }

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
        self::loadScenes();
        if (self::$scenes) {
            $scene = self::$scenes->findWith('key', $this->scene);
            if ($scene) {
                return $scene->title;
            }
        }
        return $this->scene;
    }

    public function getTheatre()
    {
        self::loadTheatres();
        if (self::$theatres) {
            return self::$theatres->findWith('key', $this->theatre);
        }
        return null;
    }

    public function getTheatreTitle()
    {
        return ($theatre = $this->getTheatre()) ? $theatre->title : '';
    }

    public function getTheatreAbbr()
    {
        return ($theatre = $this->getTheatre()) ? $theatre->abbr : '';
    }

    public function getTheatreLink()
    {
        return ($theatre = $this->getTheatre()) ? $theatre->link : '';
    }
}