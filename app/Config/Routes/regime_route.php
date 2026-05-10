<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

# REGIMES

$routes->get('/regimes', 'RegimeController::index');
$routes->get('/regimes/(:num)', 'RegimeController::show/$1');
$routes->get('/regimes/(:num)/activities', 'RegimeController::getActivities/$1');

# ACTIONS REGIME (USER SESSION)

$routes->post('/regimes/(:num)/calculate-price', 'RegimeSelectController::calculatePrice/$1');
$routes->post('/regimes/(:num)/subscribe', 'RegimeSelectController::subscribe/$1');
$routes->get('/regimes/(:num)/preview', 'RegimeSelectController::preview/$1');

# OBJECTIFS

$routes->post('/objectif', 'ObjectifController::store');
$routes->get('/objectif/me', 'ObjectifController::getMyObjectif');

# RECOMMANDATION (IMPORTANT)

$routes->get('/recommend', 'RecommendationController::recommend');
