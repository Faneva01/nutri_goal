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
    
    // Dashboard admin (protection nécessaire in middleware)
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
});
