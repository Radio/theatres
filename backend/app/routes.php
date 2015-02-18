<?php

use \Symfony\Component\HttpFoundation\Request;
use \Theatres\Controllers;

// Define Routes

// 1. System

$app->get('/system/setup', 'Theatres\\Controllers\\System_Setup::index')
    ->bind('system_setup');

$app->get('/system/export', 'Theatres\\Controllers\\System_Export::index')
    ->bind('system_export');

$app->get('/system/import', 'Theatres\\Controllers\\System_Import::index')
    ->bind('system_import');

$app->get('/system/clear', 'Theatres\\Controllers\\System_Clear::index')
    ->bind('system_clear');

// 2. Fetch

$app->get('/fetch/{theatreKey}', 'Theatres\\Controllers\\Fetch::fetch')
    ->bind('fetch_theatre');

$app->get('/fetch/test/{theatreKey}', 'Theatres\\Controllers\\Fetch::test')
    ->bind('fetch_test_theatre');

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