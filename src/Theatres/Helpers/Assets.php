<?php

namespace Theatres\Helpers;

/**
 * Help to deal with assets.
 *
 * @package Theatres\Helpers
 */
class Assets
{
    protected $siteBase = '';

    /** @var array List of styles. */
    protected $styles = array();

    /** @var array List of scripts. */
    protected $scripts = array();

    public function __construct($siteBase = '')
    {
        $this->siteBase = $siteBase;
    }

    /**
     * Add style to list.
     *
     * @param string $url Style URL.
     * @param string $media Media attribute value.
     * @param string|null $condition Condition to use in comment.
     */
    public function addStyle($url, $media = 'all', $condition = null)
    {
        $this->styles[$url] = array(
            'url' => $url,
            'media' => $media,
            'condition' => $condition,
        );
    }

    /**
     * Add script to list.
     *
     * @param string $url Style URL.
     * @param string|null $condition Condition to use in comment.
     */
    public function addScript($url, $condition = null)
    {
        $this->scripts[$url] = array(
            'url' => $url,
            'condition' => $condition,
        );
    }

    /**
     * Set scripts array.
     *
     * @param array $scripts Array of scripts.
     */
    public function setScripts($scripts)
    {
        $this->scripts = $scripts;
    }

    /**
     * Ge all scripts.
     *
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * Set styles array.
     *
     * @param array $styles Array of styles.
     */
    public function setStyles($styles)
    {
        $this->styles = $styles;
    }

    /**
     * Get all styles.
     *
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

    public function renderScript($url, $condition = null)
    {
        return Html::renderScriptTag($this->siteBase . '/' . $url, $condition);
    }

    public function renderStyle($url, $media, $condition = null)
    {
        return Html::renderStyleTag($this->siteBase . '/' . $url, $media, $condition);
    }
}