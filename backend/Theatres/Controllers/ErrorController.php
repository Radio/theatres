<?php

namespace Theatres\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    public function error404()
    {
        $message = 'На этом сайте нет такой страницы.';
        return new Response($message);
    }

    public function errorCommon()
    {
        $message = 'К сожалению, сайт сломался. К счастью, администратор уже чинит его.';
        return new Response($message);
    }
}