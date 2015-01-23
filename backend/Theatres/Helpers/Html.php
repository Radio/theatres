<?php

namespace Theatres\Helpers;

/**
 * Help to deal with html routine.
 *
 * @package Theatres\Helpers
 */
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

    public static function renderStyleTag($url, $media, $condition = null)
    {
        $html = sprintf('<link href="%s" rel="stylesheet" media="%s">', $url, $media);
        if ($condition) {
            $html = sprintf("<!--[if %s]>\n%s\n<![endif]-->", $condition, $html);
        }
        return $html;
    }

    public static function renderScriptTag($url, $condition = null)
    {
        $html = sprintf('<script src="%s"></script>', $url);
        if ($condition) {
            $html = sprintf("<!--[if %s]>\n%s\n<![endif]-->", $condition, $html);
        }
        return $html;
    }
}