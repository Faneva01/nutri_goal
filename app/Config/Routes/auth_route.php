<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Register
$routes->get('/register', 'RegisterController::index');
$routes->post('/auth/validation-input', 'RegisterController::validationInput');
$routes->post('/register', 'RegisterController::store');


// Login
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::login');

// Logout
$routes->get('/logout', 'LoginController::logout');
