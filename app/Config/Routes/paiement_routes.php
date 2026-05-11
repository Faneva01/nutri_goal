<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Routes — Paiements & Codes portefeuille
 * @var RouteCollection $routes
 */

// ── Codes portefeuille ─────────────────────────────────────────────────────
$routes->get( '/code/achat',             'CodeController::achat');
$routes->post('/code/achat',             'CodeController::traiterAchat');
$routes->get( '/code/validation',        'CodeController::validation');
$routes->post('/code/validation',        'CodeController::traiterValidation');
$routes->post('/code/verifier',          'CodeController::verifierCode');
$routes->get( '/code/historique',        'TransactionController::index');

// ── Paiements ──────────────────────────────────────────────────────────────
$routes->get( '/paiement/choisir/(:num)', 'PaiementController::choisir/$1');
$routes->post('/paiement/formulaire',     'PaiementController::formulaire');
$routes->post('/paiement/traiter',        'PaiementController::traiter');
$routes->get( '/paiement/success',        'PaiementController::success');