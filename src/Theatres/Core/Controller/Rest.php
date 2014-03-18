<?php

namespace Theatres\Core;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Theatres\Core\Exceptions\Api;
use Theatres\Core\Exceptions\Api_NotFound;
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
            return $this->output($data, 200, $app);

        } catch (Api_NotImplemented $e) {
            $message = ucfirst($method) . ' method is not implemented. ' . $e->getMessage();
            return $this->error($message, 500, $app);
        } catch (Api_NotSupported $e) {
            $message = ucfirst($method) . ' method is not supported. ' . $e->getMessage();
            return $this->error($message, 500, $app);
        } catch (Api_NotFound $e) {
            $message = $e->getMessage();
            return $this->error($message, 404, $app);
        } catch (Api $e) {
            $message = $e->getMessage();
            return $this->error($message, 500, $app);
        } catch (\Exception $e) {
            $message = 'Unknown exception: "' . $e->getMessage() . '"';
            return $this->error($message, 500, $app);
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

    protected function output($data, $status = 200, Application $app, $format = self::DEFAULT_FORMAT)
    {
        switch ($format) {
            case 'json':
                return $this->outputJson($data, $status, $app);
                break;
        }
        throw new Api_NotSupported(ucfirst($format) . ' format is not supported.');
    }

    protected function outputJson($data, $status = 200, Application $app)
    {
        return $app->json($data, $status);
    }

    protected function error($message, $status = 500, Application $app)
    {
        return $this->output(array(
            'message' => $message
        ), $status, $app);
    }
}