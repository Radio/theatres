<?php

use \Symfony\Component\HttpFoundation\Request;
use \Theatres\Controllers;

// Define Routes

// 1. Home

$app->get('/', 'Theatres\\Controllers\\Homepage::index')
    ->bind('homepage');

$app->get('/cal', 'Theatres\\Controllers\\Cal::index')
    ->bind('cal');

$app->get('/templates/{tpl}', 'Theatres\\Controllers\\AngularTemplates::index')
    ->assert('tpl', '.+?\.html')
    ->convert('tpl', function($tpl) {
        return substr($tpl, 0, -5);
    })
    ->bind('templates');

// 2. Admin

$app->get('/admin', 'Theatres\\Controllers\\Admin_Homepage::index')
    ->bind('admin');

$app->get('/admin/system/setup', 'Theatres\\Controllers\\Admin_System_Setup::index')
    ->bind('admin_system_setup');

$app->get('/admin/system/export', 'Theatres\\Controllers\\Admin_System_Export::index')
    ->bind('admin_system_export');

$app->get('/admin/system/import', 'Theatres\\Controllers\\Admin_System_Import::index')
    ->bind('admin_system_import');

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

$app->match('/api/scenes', 'Theatres\\Controllers\\Api_Scenes::call')
    ->bind('api_scenes');

$app->match('/api/scenes/{id}', 'Theatres\\Controllers\\Api_Scenes_Scene::call')
    ->assert('id', '\d+')
    ->bind('api_scenes_scene');

$app->match('/api/plays', 'Theatres\\Controllers\\Api_Plays::call')
    ->bind('api_plays');

$app->match('/api/plays/{id}', 'Theatres\\Controllers\\Api_Plays_Play::call')
    ->assert('id', '\d+')
    ->bind('api_plays_play');