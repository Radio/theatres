<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Collections\Theatres;
use Theatres\Core\Exceptions\Fetchers_UndefinedFetcher;
use Theatres\Models\Schedule;
use Theatres\Models\Theatre;
use RedBean_Facade as R;
use Theatres\Helpers\Date as DateHelper;
use Theatres\Core\Controller_Templatable as Templatable;

class Admin_Fetch
{
    use Templatable;

    public function index(Application $app)
    {
        $theatres = new Theatres();
        $theatres->setConditions('fetcher is not null and fetcher != ""');
        $theatres->setOrder('id');

        $context = array(
            'is_admin' => true,
            'theatres' => $theatres,
        );

        $this->useLayout('admin');
        return $this->renderTemplate('admin/fetch.twig', $context, $app);
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

            $shows = $fetcher->fetch($month, $year);

            $schedule = new Schedule($theatre->box());

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


}