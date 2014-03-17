<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Theatres;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;
use Theatres\Models\Theatre;
use RedBean_Facade as R;
use Theatres\Helpers\Date as DateHelper;

class Admin_Fetch
{
    public function index(Application $app)
    {
        $theatres = new Theatres();
        $theatres->setConditions('fetcher is not null and fetcher != ""');
        $theatres->setOrder('id');

        $context = array(
            'is_admin' => true,
            'theatres' => $theatres,
        );

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        return $twig->render('admin/fetch.html', $context);
    }

    public function fetch(Request $request, Application $app, $theatreKey)
    {
        $month = $request->query->getInt('month', (int) date('n'));
        $year  = $request->query->getInt('year', (int) date('Y'));

        /** @var Theatre $theatre */
        $theatre = R::dispense('theatre');
        $theatre->loadByKey($theatreKey);

        $responseData = array(
            'status' => ''
        );

        try {
            $fetcher = $theatre->getFetcher($theatre);

            $schedule = $fetcher->fetch($month, $year);

            $theatre->storeSchedule($schedule, $month, $year);

            $message = sprintf('Получили %d спектаклей на %s %d года.',
                count($schedule), DateHelper::getMonthName($month), $year);

            $responseData['status'] = 'success';
            $responseData['message'] = $message;

        } catch (Fetchers_UndefinedFetcher $e) {
            $responseData['status'] = 'failure';
            $responseData['message'] = $e->getMessage();
        }

        return $app->json($responseData);
    }


}