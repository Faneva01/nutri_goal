<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Routes pour les paiements et codes portefeuille
 *
 * @var RouteCollection $routes
 */

// Routes pour les codes portefeuille
$routes->get('/code/achat', 'CodeController::achat');
$routes->post('/code/achat', 'CodeController::traiterAchat');
$routes->get('/code/validation', 'CodeController::validation');
$routes->post('/code/validation', 'CodeController::traiterValidation');
$routes->post('/code/verifier', 'CodeController::verifierCode');
$routes->get('/code/historique', 'CodeController::historique');

// Routes pour les paiements
$routes->get('/paiement', 'PaiementController::index');
$routes->get('/paiement/process/(:segment)', 'PaiementController::choisir/$1');
$routes->post('/paiement/choisir', 'PaiementController::choisir');
$routes->post('/paiement/traiter/(:segment)', 'PaiementController::traiter/$1');
$routes->get('/paiement/success', 'PaiementController::success');
$routes->get('/paiement/verifier/(:segment)', 'PaiementController::verifierStatut/$1');