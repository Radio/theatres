<?php

namespace Theatres\Models;

use Theatres\Collections\Theatres;

class Sitemap_Builder
{
    /**
     * @return \SimpleXMLElement
     */
    public function build()
    {
        $links = $this->getAllLinks();
        $dom = $this->toXml($links);
        return $dom;
    }

    public function getAllLinks()
    {
        $baseUrl = $this->getBaseUrl();

        $homeLink = $this->getHomeLink();
        $theatresLinks = $this->getTheatresLinks();

        $allLinks = array_merge($homeLink, $theatresLinks);

        $allLinks = array_map(function($link) use ($baseUrl) {
            $link['loc'] = $baseUrl . $link['loc'];
            return $link;
        }, $allLinks);

        return $allLinks;
    }

    /**
     * @return array
     */
    protected function getHomeLink()
    {
        return [[
            'loc' => '/',
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ]];
    }

    protected function getTheatresLinks()
    {
        $links = [];
        $theatres = new Theatres();
        foreach ($theatres as $theatre) {
            $links[] = [
                'loc' => '/theatre/' . $theatre->key,
                //'lastmod' => '',
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ];
        }

        return $links;
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/#!';
    }

    protected function toXml(array $links)
    {
        $urlsetNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $urlsetNode->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        foreach ($links as $link) {
            $urlNode = $urlsetNode->addChild('url');
            foreach ($link as $linkAttrName => $linkAttrValue) {
                $urlNode->addChild($linkAttrName, $linkAttrValue);
            }
        }

        return $urlsetNode;
    }

}