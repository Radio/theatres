<?php

namespace Theatres\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

class System_Setup
{
    public function index(Request $request, Application $app)
    {
        $username = $request->query->get('user');
        $password = $request->query->get('pass');
        // find the encoder for a UserInterface instance
        $user = new User($username, $password);
        $encoder = $app['security.encoder_factory']->getEncoder($user);

        // compute the encoded password for foo
        return $encoder->encodePassword($password, $user->getSalt());
    }
}