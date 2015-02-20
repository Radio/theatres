<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \RedBean_Facade as R;
use Theatres\Models\Sitemap_Builder;

class System_Sitemap
{
    public function index(Request $request, Application $app)
    {
        $builder = new Sitemap_Builder();
        $dom = $builder->build();

        $dom->asXML($app['dir.root'] . '/sitemap.xml');

        return 'ok';
    }

    public function plain(Request $request, Application $app)
    {
        $builder = new Sitemap_Builder();
        $links = $builder->getAllLinks();

        $sitemap = '';
        foreach ($links as $link) {
            $sitemap .= $link['loc'] . PHP_EOL;
        }

        file_put_contents($app['dir.root'] . '/sitemap.txt', trim($sitemap));

        return 'ok';
    }
}