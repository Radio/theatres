<?php

namespace Theatres\Core;

interface Fetcher_Interface
{
    public function getTheatreId();
    public function fetch($month, $year);
}