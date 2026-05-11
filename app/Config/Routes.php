<?php

use App\Controllers\DashboardUserController;    
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get("/", [DashboardUserController::class, 'index']);

require APPPATH . 'Config/Routes/auth_route.php';
require APPPATH . 'Config/Routes/dashboard_user_routes.php';
require APPPATH . 'Config/Routes/regime_route.php';
require APPPATH . 'Config/Routes/paiement_routes.php';
require APPPATH . 'Config/Routes/profil_route.php';
require APPPATH . 'Config/Routes/portefeuille_routes.php';
require APPPATH . 'Config/Routes/admin_routes.php';