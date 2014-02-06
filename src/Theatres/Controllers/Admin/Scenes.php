<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Theatres\Collections\Scenes;
use Theatres\Core\Message;
use RedBean_Facade as R;

class Admin_Scenes
{
    public function index(Application $app)
    {
        $scenes = new Scenes();
        $scenes->setOrder('id');

        $context = array(
            'scenes' => $scenes,
        );

        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        return $twig->render('admin/scenes.html', $context);
    }

    public function save(Request $request, Application $app)
    {
        $scenesData = $this->getScenesData($request);

        foreach ($scenesData as $sceneId => $sceneData) {
            $scene = R::load('scene', (int) $sceneId);
            $scene->import($sceneData);
            R::store($scene);
        }

        /** @var Session $session */
        $session = $app['session'];
        $session->getFlashBag()->add('message', new Message('Сцены сохранены.', 'success'));

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];
        return $app->redirect($urlGenerator->generate('admin_scenes_list'));
    }

    public function delete(Request $request, Application $app)
    {
        $sceneId = $request->request->get('id');
        /** @var Session $session */
        $session = $app['session'];
        $flashBag = $session->getFlashBag();

        if ($sceneId) {
            $scene = R::load('scene', $sceneId);
            if ($scene->id) {
                R::trash($scene);
                $flashBag->add('message', new Message('Сцена удалена.', 'success'));
            } else {
                $flashBag->add('message', new Message("Сцены с ID '$sceneId' не существует.", 'error'));
            }
        } else {
            $flashBag->add('message', new Message('ID сцены не передан.', 'error'));
        }

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];
        return $app->redirect($urlGenerator->generate('admin_scenes_list'));
    }

    protected function getScenesData(Request $request)
    {
        $scenesData = array();
        $postData = $request->request->get('scenes');

        foreach ($postData as $sceneId => $sceneData) {
            $dataIsEmpty = true;
            foreach ($sceneData as $attrValue) {
                if (trim($attrValue) !== '') {
                    $dataIsEmpty = false;
                    break;
                }
            }
            if (!$dataIsEmpty) {
                $scenesData[$sceneId] = $this->prepareScenesData($sceneData);
            }
        }

        return $scenesData;
    }

    protected function prepareScenesData($data)
    {
        array_walk($data, 'trim');
        return $data;
    }
}