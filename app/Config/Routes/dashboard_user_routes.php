<?php

use App\Controllers\DashboardUserController;

$routes->get('dashboard', [DashboardUserController::class, 'index']);
$routes->get('dashboard-user', [DashboardUserController::class, 'index']);
