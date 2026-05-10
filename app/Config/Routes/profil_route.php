<?php

use App\Controllers\ProfilController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// URLs principales de la page profil
$routes->get('/profil', [ProfilController::class, 'index']);
$routes->get('/profil-page', [ProfilController::class, 'index']);

// Actions AJAX du profil
$routes->post('/profil/update', [ProfilController::class, 'update']);
$routes->post('/profil/toggleGold', [ProfilController::class, 'toggleGold']);
$routes->post('/profil/rechargerSolde', [ProfilController::class, 'rechargerSolde']);