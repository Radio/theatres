<?php

namespace Theatres\Helpers;

class Html
{
    public static function stripTags($html, $exclude = null)
    {
        $html = str_replace('&nbsp;', ' ', $html);
        $text = trim(strip_tags($html, $exclude));

        return $text;
    }

    public static function fixSpaces($html)
    {
        return preg_replace('/\s+/', ' ', $html);
    }

    public static function fixUrl($url, $baseUrl)
    {
        if (preg_match('/^\//', $url)) {
            $base = parse_url($baseUrl);
            $url = sprintf('%s://%s%s', $base['scheme'], $base['host'], $url);
        } elseif (!preg_match('/^http/', $url)) {
            $base = parse_url($baseUrl);
            $path = substr($base['path'], 0, strrpos($base['path'], '/'));
            $url = sprintf('%s://%s%s/%s', $base['scheme'], $base['host'], $path, $url);
        }
        return $url;
    }
}