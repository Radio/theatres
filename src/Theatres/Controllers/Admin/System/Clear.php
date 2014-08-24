<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use \RedBean_Facade as R;

class Admin_System_Clear
{
    public function index(Request $request, Application $app)
    {
        R::exec('delete from `play_tag`');
        R::exec('delete from `play`');
        R::exec('delete from `show`');
        R::exec('delete from `tag`');

        return 'ok';
    }
}