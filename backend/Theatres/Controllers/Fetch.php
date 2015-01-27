<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Theatres;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;
use Theatres\Core\Factory;
use Theatres\Models\Schedule;
use Theatres\Models\Theatre;
use RedBean_Facade as R;
use Theatres\Helpers\Date as DateHelper;
use Theatres\Core\Controller_Templatable as Templatable;

class Fetch
{
    use Templatable;

    public function fetch(Request $request, Application $app, $theatreKey)
    {
        $month = $request->query->getInt('month', (int) date('n'));
        $year  = $request->query->getInt('year', (int) date('Y'));

        $responseData = array(
            'status' => ''
        );

        /** @var Theatre $theatre */
        $theatre = R::dispense('theatre');
        $theatre->loadByKey($theatreKey);

        if (!$theatre->id) {
            $responseData['status'] = 'failure';
            $responseData['message'] = 'Theatre doesn\'t exists.';
            return $app->json($responseData);
        }

        try {
            /** @var Factory $factory */
            $factory = $app['factory'];
            $fetcher = $factory->getTheatreFetcher($theatreKey);
            $shows = $fetcher->fetch($month, $year);

            $schedule = $factory->getTheatreSchedule($theatre->box());
            $schedule->saveSchedule($shows, $month, $year);

            $message = sprintf('Получили %d спектаклей на %s %d года.',
                count($shows), DateHelper::getMonthName($month), $year);

            $responseData['status'] = 'success';
            $responseData['message'] = $message;

        } catch (Fetchers_UndefinedFetcher $e) {
            $responseData['status'] = 'failure';
            $responseData['message'] = $e->getMessage();
        }

        return $app->json($responseData);
    }

    public function test(Request $request, Application $app, $theatreKey)
    {
        $month = $request->query->getInt('month', (int) date('n'));
        $year  = $request->query->getInt('year', (int) date('Y'));

        $responseData = array(
            'status' => ''
        );

        /** @var Theatre $theatre */
        $theatre = R::dispense('theatre');
        $theatre->loadByKey($theatreKey);

        if (!$theatre->id) {
            $responseData['status'] = 'failure';
            $responseData['message'] = 'Theatre doesn\'t exists.';
            return $app->json($responseData);
        }

        try {
            /** @var Factory $factory */
            $factory = $app['factory'];
            $fetcher = $factory->getTheatreFetcher($theatreKey);
            $shows = $fetcher->fetch($month, $year);

            $responseData['status'] = 'success';
            $responseData['shows'] = $shows;

        } catch (Fetchers_UndefinedFetcher $e) {
            $responseData['status'] = 'failure';
            $responseData['message'] = $e->getMessage();
        }

        return $app->json($responseData);
    }
}