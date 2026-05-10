<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

# REGIMES

$routes->get('/regime', 'RegimeController::index');
$routes->get('/regime/(:num)', 'RegimeController::show/$1');
$routes->get('/regime/(:num)/activities', 'RegimeController::getActivities/$1');

# ACTIONS REGIME (USER SESSION)

$routes->post('/regime/(:num)/calculate-price', 'RegimeSelectController::calculatePrice/$1');
$routes->post('/regime/(:num)/subscribe', 'RegimeSelectController::subscribe/$1');
$routes->get('/regime/(:num)/preview', 'RegimeSelectController::preview/$1');

# OBJECTIFS

$routes->post('/objectif', 'ObjectifController::store');
$routes->get('/objectif/me', 'ObjectifController::getMyObjectif');

# RECOMMANDATION (IMPORTANT)

$routes->get('/recommend', 'RecommendationController::recommend');
