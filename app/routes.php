<?php

use \Symfony\Component\HttpFoundation\Request;
use \Theatres\Controllers;

// Define Routes

// 1. Home and Front App

$app->get('/', 'Theatres\\Controllers\\FrontApp::index')
    ->bind('homepage');

$app->get('/templates/{tpl}', 'Theatres\\Controllers\\AngularTemplates::index')
    ->assert('tpl', '.+?\.html')
    ->convert('tpl', function($tpl) {
        return substr($tpl, 0, -5);
    })
    ->bind('templates');

$app->get('/month', 'Theatres\\Controllers\\FrontApp::index')
    ->bind('front_app.month');

$app->get('/play/{id}', 'Theatres\\Controllers\\FrontApp::index')
    ->assert('id', '\d+')
    ->bind('front_app.play');

// 2. Admin

$app->get('/admin', 'Theatres\\Controllers\\Admin_App::index')
    ->bind('admin');

$app->get('/admin/system/setup', 'Theatres\\Controllers\\Admin_System_Setup::index')
    ->bind('admin_system_setup');

$app->get('/admin/system/export', 'Theatres\\Controllers\\Admin_System_Export::index')
    ->bind('admin_system_export');

$app->get('/admin/system/import', 'Theatres\\Controllers\\Admin_System_Import::index')
    ->bind('admin_system_import');

// 2.1 Admin → Theatres

$app->get('/admin/theatres', 'Theatres\\Controllers\\Admin_App::index')
    ->bind('admin_theatres_list');

// 2.3 Admin → Scenes

$app->get('/admin/scenes', 'Theatres\\Controllers\\Admin_App::index')
    ->bind('admin_scenes_list');

// 2.4 Admin → Plays

$app->get('/admin/plays', 'Theatres\\Controllers\\Admin_App::index')
    ->bind('admin_plays_list');

// 2.5 Admin → Shows

$app->get('/admin/shows', 'Theatres\\Controllers\\Admin_App::index')
    ->bind('admin_shows_list');

// 2.6 Admin → Fetch

$app->get('/admin/fetch', 'Theatres\\Controllers\\Admin_App::index')
    ->bind('admin_fetch');

$app->get('/admin/fetch/{theatreKey}', 'Theatres\\Controllers\\Admin_Fetch::fetch')
    ->bind('admin_fetch_theatre');

// 3. API

$app->match('/api/theatres', 'Theatres\\Controllers\\Api_Theatres::call')
    ->bind('api_theatres');

$app->match('/api/theatres/{id}', 'Theatres\\Controllers\\Api_Theatres_Theatre::call')
    ->assert('id', '\d+|@[a-z\-_0-9]+')
    ->bind('api_theatres_theatre');

$app->match('/api/scenes', 'Theatres\\Controllers\\Api_Scenes::call')
    ->bind('api_scenes');

$app->match('/api/scenes/{id}', 'Theatres\\Controllers\\Api_Scenes_Scene::call')
    ->assert('id', '\d+|@[a-z\-_0-9]+')
    ->bind('api_scenes_scene');

$app->match('/api/plays', 'Theatres\\Controllers\\Api_Plays::call')
    ->bind('api_plays');

$app->match('/api/plays/{id}', 'Theatres\\Controllers\\Api_Plays_Play::call')
    ->assert('id', '\d+|@[a-z\-_0-9]+')
    ->bind('api_plays_play');

$app->match('/api/plays/{playId}/tags', 'Theatres\\Controllers\\Api_Plays_Play_Tags::call')
    ->assert('playId', '\d+|@[a-z\-_0-9]+')
    ->bind('api_plays_play_tags');

$app->match('/api/shows', 'Theatres\\Controllers\\Api_Shows::call')
    ->bind('api_shows');

$app->match('/api/shows/{id}', 'Theatres\\Controllers\\Api_Shows_Show::call')
    ->assert('id', '\d+|@[a-z\-_0-9]+')
    ->bind('api_shows_show');

$app->match('/api/tags', 'Theatres\\Controllers\\Api_Tags::call')
    ->bind('api_tags');

$app->match('/api/tags/{id}', 'Theatres\\Controllers\\Api_Tags_Tag::call')
    ->assert('id', '\d+|@[a-z\-_0-9]+')
    ->bind('api_tags_tag');