<?php

$siteBase = '/theatres/web';

$debug  = true;
$secure = false;

$db = array();
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = 'root';
$db['name'] = 'theatres';

$users = [
    'admin' => [
        'ROLE_ADMIN',
        '%PASSWORD_HASH%'
    ],
];
