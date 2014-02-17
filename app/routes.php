<?php

use \Symfony\Component\HttpFoundation\Request;
use \Theatres\Controllers;

// Define Routes

// 1. Home

$app->get('/', 'Theatres\\Controllers\\Homepage::index')
    ->bind('homepage');

// 2. Admin

$app->get('/admin', 'Theatres\\Controllers\\Admin_Homepage::index')
    ->bind('admin');

$app->get('/admin/setup', 'Theatres\\Controllers\\Admin_Setup::index')
    ->bind('admin_setup');

// 2.1 Admin → Theatres

$app->get('/admin/theatres', 'Theatres\\Controllers\\Admin_Theatres::index')
    ->bind('admin_theatres_list');

$app->post('/admin/theatres', 'Theatres\\Controllers\\Admin_Theatres::save')
    ->bind('admin_theatres_save');

$app->post('/admin/theatres/delete', 'Theatres\\Controllers\\Admin_Theatres::delete')
    ->bind('admin_theatres_delete');

// 2.3 Admin → Scenes

$app->get('/admin/scenes', 'Theatres\\Controllers\\Admin_Scenes::index')
    ->bind('admin_scenes_list');

$app->post('/admin/scenes', 'Theatres\\Controllers\\Admin_Scenes::save')
    ->bind('admin_scenes_save');

$app->post('/admin/scenes/delete', 'Theatres\\Controllers\\Admin_Scenes::delete')
    ->bind('admin_scenes_delete');


// 2.4 Admin → Fetch

$app->get('/admin/fetch', 'Theatres\\Controllers\\Admin_Fetch::index')
    ->bind('admin_fetch');

$app->get('/admin/fetch/{theatreKey}', 'Theatres\\Controllers\\Admin_Fetch::fetch')
    ->bind('admin_fetch_theatre');

// 3. API

$app->match('/api/theatres', 'Theatres\\Controllers\\Api_Theatres::call')
    ->bind('api_theatres');

$app->match('/api/theatres/{id}', 'Theatres\\Controllers\\Api_Theatres_Theatre::call')
    ->assert('id', '\d+')
    ->bind('api_theatres_theatre');
