<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Principal::index');
$routes->get('/jornada', 'Jornada::index');
