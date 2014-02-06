<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Theatres\Collections\Theatres;
use Theatres\Core\Message;
use RedBean_Facade as R;

class Admin_Theatres
{
    public function index(Application $app)
    {
        $theatres = new Theatres();
        $theatres->setOrder('id');

        $context = array(
            'theatres' => $theatres,
        );

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        return $twig->render('admin/theatres.html', $context);
    }

    public function save(Request $request, Application $app)
    {
        $theatresData = $this->getTheatresData($request);

        foreach ($theatresData as $theatreId => $theatreData) {
            $theatre = R::load('theatre', (int) $theatreId);
            $theatre->import($theatreData);
            R::store($theatre);
        }

        /** @var Session $session */
        $session = $app['session'];
        $session->getFlashBag()->add('message', new Message('Театры сохранены.', 'success'));

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];
        return $app->redirect($urlGenerator->generate('admin_theatres_list'));
    }

    public function delete(Request $request, Application $app)
    {
        $theatreId = $request->request->get('id');
        /** @var Session $session */
        $session = $app['session'];
        $flashBag = $session->getFlashBag();

        if ($theatreId) {
            $theatre = R::load('theatre', $theatreId);
            if ($theatre->id) {
                R::trash($theatre);
                $flashBag->add('message', new Message('Театр удален.', 'success'));
            } else {
                $flashBag->add('message', new Message("Театра с ID '$theatreId' не существует.", 'error'));
            }
        } else {
            $flashBag->add('message', new Message('ID театра не передан.', 'error'));
        }

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];
        return $app->redirect($urlGenerator->generate('admin_theatres_list'));
    }

    protected function getTheatresData(Request $request)
    {
        $theatresData = array();
        $postData = $request->request->get('theatres');

        foreach ($postData as $theatreId => $theatreData) {
            $dataIsEmpty = true;
            foreach ($theatreData as $attrValue) {
                if (trim($attrValue) !== '') {
                    $dataIsEmpty = false;
                    break;
                }
            }
            if (!$dataIsEmpty) {
                $theatresData[$theatreId] = $this->prepareTheatreData($theatreData);
            }
        }

        return $theatresData;
    }

    protected function prepareTheatreData($data)
    {
        array_walk($data, 'trim');
        return $data;
    }
}