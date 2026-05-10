<?php

use App\Controllers\DashboardUserController;    
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/dashboard', [DashboardUserController::class, 'index']);
$routes->get('/dashboard-user', [DashboardUserController::class, 'index']);
