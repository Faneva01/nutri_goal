<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Authentification — la racine du site (/) affiche la page de connexion.
 *
 * @var RouteCollection $routes
 */

// Connexion : première page du site + alias /login
$routes->get('/', 'LoginController::index');
$routes->get('login', 'LoginController::index');
$routes->post('login', 'LoginController::login');

// Inscription
$routes->get('register', 'RegisterController::index');
$routes->post('register', 'RegisterController::store');
$routes->post('auth/validation-input', 'RegisterController::validationInput');
