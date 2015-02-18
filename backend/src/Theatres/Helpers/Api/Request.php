<?php

namespace Theatres\Helpers;

use Symfony\Component\HttpFoundation\Request;

/**
 * Help to deal with API requests.
 *
 * @package Theatres\Helpers
 */
class Api_Request
{
    /**
     * Get json-decoded data from request content.
     *
     * @param Request $request Request instance.
     * @param bool $assoc Decode data as array instead of object.
     * @return array|object|null
     */
    public static function getRawData(Request $request, $assoc = true)
    {
        $content = $request->getContent();
        try {
            $data = json_decode($content, $assoc);
        } catch (\Exception $e) {
            $data = null;
        }

        return $data;
    }

    /**
     * Alias of getRawData() for POST request.
     *
     * @param Request $request Request instance.
     * @param bool $assoc Decode data as array instead of object.
     * @return array|null|object
     */
    public static function getPostData(Request $request, $assoc = true)
    {
        return self::getRawData($request, $assoc);
    }

    /**
     * Alias of getRawData() for PUT request.
     *
     * @param Request $request Request instance.
     * @param bool $assoc Decode data as array instead of object.
     * @return array|null|object
     */
    public static function getPutData(Request $request, $assoc = true)
    {
        return self::getRawData($request, $assoc);
    }
}