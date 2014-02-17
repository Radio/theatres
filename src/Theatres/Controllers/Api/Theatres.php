<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Theatres;
use Theatres\Core\Controller_Rest;
use Theatres\Helpers\Api;

class Api_Theatres extends Controller_Rest
{
    const DEFAULT_ORDER = 'title';

    private static $allowedOrders = array(
        'id', 'title'
    );

    public function get(Application $app, Request $request)
    {
        $fetchable = Api::toBool($request->query->get('fetchable'));
        $order = $request->query->get('order', self::DEFAULT_ORDER);
        if (!Api::isAllowed($order, self::$allowedOrders)) {
            $order = self::DEFAULT_ORDER;
        }

        $theatres = new Theatres();

        if (!is_null($fetchable)) {
            if ($fetchable) {
                $theatres->addConditions('fetcher is not null and fetcher != ""');
            } else {
                $theatres->addConditions('(fetcher is null or fetcher = "")');
            }
        }
        if ($order) {
            $theatres->setOrder($order);
        }

        return $theatres->toArray();
    }
}