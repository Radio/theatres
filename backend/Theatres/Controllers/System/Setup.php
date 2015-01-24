<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \RedBean_Facade as R;

class System_Setup
{
    public function index(Request $request, Application $app)
    {
        $this->setupScenes();
        $this->setupTheatres();

        return 'Set up ok.';
    }

    protected function setupScenes()
    {
        $scenes = array(
            'main'  => 'главная сцена',
            'small' => 'малая сцена',
            'big'   => 'большая сцена',
            'exp'   => 'экспериментальная сцена',
        );

        $type = 'scene';

        R::wipe($type);

        $beans = array();
        foreach ($scenes as $key => $title) {
            $scene = R::dispense($type);
            $scene->key = $key;
            $scene->title = $title;
            $beans[] = $scene;
        }
        R::storeAll($beans);
    }

    protected function setupTheatres()
    {
        $theatres = array(
            'theatre19' => array(
                'title' => 'Театр 19',
                'abbr' => 'Т19',
                'link' => 'http://www.theatre19.com.ua',
                'has_fetcher' => '1',
            ),
            'shevchenko' => array(
                'title' => 'Театр им. Шевченко',
                'abbr' => 'ТШ',
                'link' => 'http://www.theatre-shevchenko.com.ua',
                'has_fetcher' => '1',
            ),
            'postscriptum' => array(
                'title' => 'Театр «PostScriptum»',
                'abbr' => 'PS',
                'link' => 'http://ps-teatr.com.ua',
                'has_fetcher' => '1',
            ),
            'pushkin' => array(
                'title' => 'Театр им. Пушкина',
                'abbr' => 'ТП',
                'link' => 'http://rusdrama.kh.ua',
                'has_fetcher' => '1',
            ),
        );

        $type = 'theatre';

        R::wipe($type);

        $beans = array();
        foreach ($theatres as $key => $data) {
            $theatre = R::dispense($type);
            $theatre->key = $key;
            $theatre->title = $data['title'];
            $theatre->abbr = $data['abbr'];
            $theatre->link = $data['link'];
            $theatre->has_fetcher = $data['has_fetcher'];
            $beans[] = $theatre;
        }
        R::storeAll($beans);
    }
}