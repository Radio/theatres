<?php

namespace Theatres\Core;

use Symfony\Component\HttpFoundation\Response;
use Theatres\Controllers\ErrorController;

class ErrorHandler
{
    public function handleKernelException(\Exception $e, $code)
    {
        switch ($code) {
            case 404:
                $method = 'error404';
                break;
            default:
                $method = 'errorCommon';
        }

        $errorController = new ErrorController();

        if (is_callable(array($errorController, $method))) {
            $response = $errorController->$method();
        } else {
            $response = new Response('Unhandled error: "' . $e->getMessage() . '"');
        }

        return $response;
    }
}