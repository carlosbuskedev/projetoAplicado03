<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -------------------------------------------------------------------------
// Frontend — páginas (views)
// -------------------------------------------------------------------------
$routes->group('', ['namespace' => 'App\Controllers\Frontend'], static function ($routes) {
    $routes->get('/', 'Login::index');
    $routes->get('login', 'Login::index');
    $routes->get('home', 'Home::index');
    $routes->get('home-admin', 'HomeAdmin::index');
    $routes->get('journey', 'Journey::index');
    $routes->get('users', 'Users::index');
    $routes->get('quests', 'Quests::index');
    $routes->get('leveling', 'Leveling::index');
    $routes->get('side-quests', 'SideQuests::index');
    $routes->get('weekly-diagnostic', 'WeeklyDiagnostic::index');
});

// -------------------------------------------------------------------------
// Backend — API
// -------------------------------------------------------------------------
$routes->group('api/users', [
    'namespace' => 'App\Controllers\Backend',
    'filter'    => ['jwt', 'role:admin'],
], static function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('(:num)', 'Users::show/$1');
    $routes->post('/', 'Users::create');
    $routes->put('(:num)', 'Users::update/$1');
    $routes->patch('(:num)', 'Users::update/$1');
    $routes->delete('(:num)', 'Users::delete/$1');
});

$routes->group('api/quests', [
    'namespace' => 'App\Controllers\Backend',
    'filter'    => 'jwt',
], static function ($routes) {
    $routes->get('/', 'Quests::index');
    $routes->post('/', 'Quests::create');
    $routes->patch('(:num)', 'Quests::update/$1');
});

$routes->group('api/sidequests', [
    'namespace' => 'App\Controllers\Backend',
    'filter'    => 'jwt',
], static function ($routes) {
    $routes->post('/', 'SideQuests::create');
});

$routes->group('api/weekly-diagnostic', [
    'namespace' => 'App\Controllers\Backend',
    'filter'    => 'jwt',
], static function ($routes) {
    $routes->post('summary', 'WeeklyDiagnostic::summary');
    $routes->post('initialize', 'WeeklyDiagnostic::initialize');
});

$routes->group('api/auth', ['namespace' => 'App\Controllers\Backend'], static function ($routes) {
    $routes->post('login', 'Auth::login');

    $routes->group('', ['filter' => 'jwt'], static function ($routes) {
        $routes->get('me', 'Auth::me');
        $routes->get('admin', 'Auth::admin', ['filter' => ['jwt', 'role:admin']]);
    });
});
