<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

require APPPATH . 'Config/Routes/auth_route.php';
require APPPATH . 'Config/Routes/regime_route.php';
require APPPATH . 'Config/Routes/paiement_routes.php';

// Routes profil (fichier séparé)
require __DIR__ . '/Routes/profil_route.php';

// Routes dashboard utilisateur (fichier séparé)
require __DIR__ . '/Routes/dashboard_user_routes.php';

// Routes administrateur (back-office)
require __DIR__ . '/Routes/admin_routes.php';

