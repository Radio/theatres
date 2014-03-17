<?php

$autoloadPath = __DIR__.'/../vendor/autoload.php';

if (!is_readable($autoloadPath)) {
    die('Autoload file is not available. Please update vendor libraries via composer.');
}

require_once $autoloadPath;