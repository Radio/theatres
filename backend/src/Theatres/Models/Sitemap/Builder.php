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

    /**
     * @return array
     */
    protected function getTheatresLinks()
    {
        $links = [];

        $today = new \DateTime();
        $currentMonth = (int) $today->format('m');
        $currentYear = (int) $today->format('Y');
        $nextMonth = ($currentMonth + 1) % 12;
        $nextMonthYear = $currentYear + ($nextMonth > 1 ? 0 : 1);

        $monthParts = [
            $currentYear . '-' . $currentMonth,
            $nextMonthYear . '-' . $nextMonth
        ];

        $theatres = new Theatres();
        foreach ($monthParts as $monthPart) {
            foreach ($theatres as $theatre) {
                $links[] = [
                    'loc' => '/month/' . $monthPart . '/theatre/' . $theatre->key,
                    //'lastmod' => '',
                    'changefreq' => 'monthly',
                    'priority' => '0.5'
                ];
            }
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