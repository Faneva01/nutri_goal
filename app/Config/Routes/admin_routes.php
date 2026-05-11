<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes administrateur
$routes->group('admin', static function($routes) {
    // Authentification admin
    $routes->get('login', 'AdminAuthController::login');
    $routes->post('auth/verify', 'AdminAuthController::verify');
    $routes->get('auth/logout', 'AdminAuthController::logout');
    
    // Dashboard admin
    $routes->get('dashboard', 'AdminAuthController::dashboard');
    
    // Statistiques - Utilisateurs
    $routes->get('stats/usuarios', 'StatUtilisateurController::index');
    $routes->get('api/stats/usuarios', 'StatUtilisateurController::getChartData');
    
    // Statistiques - Types d'utilisateurs
    $routes->get('stats/type-usuarios', 'StatTypeUtilisateurController::index');
    $routes->get('api/stats/type-usuarios', 'StatTypeUtilisateurController::getChartData');
    $routes->get('api/stats/type-usuarios/detailed', 'StatTypeUtilisateurController::getDetailedStats');
    
    // Statistiques - Chiffre d'affaires
    $routes->get('stats/chiffre-affaire', 'StatChiffreAffaireController::index');
    $routes->get('api/stats/chiffre-affaire', 'StatChiffreAffaireController::getChartData');
    $routes->get('api/stats/chiffre-affaire/payment-methods', 'StatChiffreAffaireController::getPaymentMethods');
    $routes->get('api/stats/chiffre-affaire/stats', 'StatChiffreAffaireController::getStats');
    
    // Statistiques - Régimes et plats
    $routes->get('stats/regime', 'StatRegimeController::index');
    $routes->get('api/stats/regime', 'StatRegimeController::getChartData');
    $routes->get('api/stats/regime/dishes', 'StatRegimeController::getDishesChart');
    $routes->get('api/stats/regime/detailed', 'StatRegimeController::getDetailedStats');

    // CRUD - Régimes
    $routes->get('regimes', 'Admin\RegimeCrudController::index');
    $routes->get('regimes/create', 'Admin\RegimeCrudController::create');
    $routes->post('regimes/store', 'Admin\RegimeCrudController::store');
    $routes->get('regimes/edit/(:num)', 'Admin\RegimeCrudController::edit/$1');
    $routes->post('regimes/update/(:num)', 'Admin\RegimeCrudController::update/$1');
    $routes->get('regimes/delete/(:num)', 'Admin\RegimeCrudController::delete/$1');

    // CRUD - Activités
    $routes->get('activites', 'Admin\ActivityCrudController::index');
    $routes->get('activites/create', 'Admin\ActivityCrudController::create');
    $routes->post('activites/store', 'Admin\ActivityCrudController::store');
    $routes->get('activites/edit/(:num)', 'Admin\ActivityCrudController::edit/$1');
    $routes->post('activites/update/(:num)', 'Admin\ActivityCrudController::update/$1');
    $routes->get('activites/delete/(:num)', 'Admin\ActivityCrudController::delete/$1');

    // CRUD - Codes
    $routes->get('codes', 'Admin\CodeCrudController::index');
    $routes->get('codes/create', 'Admin\CodeCrudController::create');
    $routes->post('codes/store', 'Admin\CodeCrudController::store');
    $routes->get('codes/delete/(:num)', 'Admin\CodeCrudController::delete/$1');
});
