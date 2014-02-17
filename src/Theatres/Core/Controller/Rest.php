<?php

namespace Theatres\Core;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Exceptions\Api_NotImplemented;
use Theatres\Core\Exceptions\Api_NotSupported;

abstract class Controller_Rest
{
    const DEFAULT_FORMAT = 'json';

    public function call(Application $app, Request $request, $id = null)
    {
        $method = $request->getMethod();
        $handler = strtolower($method);
        try {

            if ($id !== null) {
                $this->loadElement($id);
            }

            $data = $this->$handler($app, $request);
            return $this->output($data, $app);

        } catch (Api_NotImplemented $e) {
            $message = ucfirst($method) . ' method is not implemented. ' . $e->getMessage();
            return $this->error($message, $app);
        } catch (Api_NotSupported $e) {
            $message = ucfirst($method) . ' method is not supported. ' . $e->getMessage();
            return $this->error($message, $app);
        } catch (\Exception $e) {
            $message = 'Unknown exception: "' . $e->getMessage() . '"';
            return $this->error($message, $app);
        }
    }

    protected function loadElement($id)
    {
        throw new Api_NotImplemented('Loading element is not implemented.');
    }

    public function get(Application $app, Request $request)
    {
        throw new Api_NotImplemented();
    }

    public function post(Application $app, Request $request)
    {
        throw new Api_NotImplemented();
    }

    public function put(Application $app, Request $request)
    {
        throw new Api_NotImplemented();
    }

    public function delete(Application $app, Request $request)
    {
        throw new Api_NotImplemented();
    }

    protected function output($data, Application $app, $format = self::DEFAULT_FORMAT)
    {
        switch ($format) {
            case 'json':
                return $this->outputJson($data, $app);
                break;
        }
        throw new Api_NotSupported(ucfirst($format) . ' format is not supported.');
    }

    protected function outputJson($data, Application $app)
    {
        return $app->json($data);
    }

    protected function error($message, $app)
    {
        return $this->output(array(
            'message' => $message
        ), $app);
    }
}