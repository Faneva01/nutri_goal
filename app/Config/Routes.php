<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'HomeController::index');
$routes->get('/produits','Produit::index');
$routes->get('/produit/(:num)', 'Produit::show/$1');

// Test composant
$routes->get('/test', 'TestComposantController::index');

// Routes profil (fichier séparé)
require __DIR__ . '/Routes/profil_route.php';

// Routes dashboard utilisateur (fichier séparé)
require __DIR__ . '/Routes/dashboard_user_routes.php';