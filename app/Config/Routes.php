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

require APPPATH . 'Config/Routes/auth_route.php';
require APPPATH . 'Config/Routes/paiement_routes.php';
