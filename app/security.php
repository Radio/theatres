<?php

use \Symfony\Component\HttpFoundation\RequestMatcher;

$users = include(__DIR__ . '/users.php');

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/admin',
            'http' => true,
            'users' => $users,
        ),
        'api' => array(
            'pattern' => new RequestMatcher('^/api', null, array('POST', 'PUT', 'DELETE')),
            'http' => true,
            'users' => $users,
        ),
    )
));
