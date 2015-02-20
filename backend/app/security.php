<?php

use \Symfony\Component\HttpFoundation\RequestMatcher;

$protectApiMethods = ['POST', 'PUT', 'DELETE'];
if (isset($_SERVER['HTTP_REFERER'])
    && strpos($_SERVER['HTTP_REFERER'], '/admin/') !== false) {
    $protectApiMethods[] = 'GET';
}

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/admin',
            'http' => true,
            'users' => $users,
        ),
        'api' => array(
            'pattern' => new RequestMatcher('^/api', null, $protectApiMethods),
            'http' => true,
            'users' => $users,
        ),
    )
));
