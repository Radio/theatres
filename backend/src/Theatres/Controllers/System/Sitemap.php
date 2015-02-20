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
}