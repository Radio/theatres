<?php

namespace Theatres\Core;

class Message
{
    protected $status = 'info';

    protected $text = '';

    public function __construct($text, $status)
    {
        $this->status = $status;
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->getText();
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}