<?php
// ============================================================
// ROUTES BACK OFFICE – admin_routes.php
// app/Config/admin_routes.php
// Inclure dans app/Config/Routes.php :  require APPPATH.'Config/admin_routes.php';
// ============================================================

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    // ── Auth ────────────────────────────────────────────────
    $routes->get('login',              'AdminAuthController::index');
    $routes->post('login',             'AdminAuthController::login');
    $routes->get('logout',             'AdminAuthController::logout');

    // ── Dashboard ───────────────────────────────────────────
    $routes->get('/',                  'DashboardAdminController::index');
    $routes->get('dashboard',          'DashboardAdminController::index');

    // ── Stats (AJAX endpoints) ───────────────────────────────
    $routes->get('stats/utilisateurs', 'StatUtilisateurController::index');
    $routes->get('stats/types',        'StatTypeUtilisateurController::index');
    $routes->get('stats/ca',           'StatChiffreAffaireController::index');
    $routes->get('stats/regimes',      'StatRegimeController::index');

    // ── CRUD Régimes ────────────────────────────────────────
    $routes->get('regimes',            'CrudRegimeController::index');
    $routes->get('regimes/create',     'CrudRegimeController::create');
    $routes->post('regimes/store',     'CrudRegimeController::store');
    $routes->get('regimes/edit/(:num)','CrudRegimeController::edit/$1');
    $routes->post('regimes/update/(:num)','CrudRegimeController::update/$1');
    $routes->post('regimes/delete/(:num)','CrudRegimeController::delete/$1');

    // ── CRUD Activités ──────────────────────────────────────
    $routes->get('activites',          'CrudActiviteController::index');
    $routes->get('activites/create',   'CrudActiviteController::create');
    $routes->post('activites/store',   'CrudActiviteController::store');
    $routes->get('activites/edit/(:num)','CrudActiviteController::edit/$1');
    $routes->post('activites/update/(:num)','CrudActiviteController::update/$1');
    $routes->post('activites/delete/(:num)','CrudActiviteController::delete/$1');

    // ── CRUD Codes portefeuille ─────────────────────────────
    $routes->get('codes',              'CrudCodeController::index');
    $routes->post('codes/valider/(:num)','CrudCodeController::valider/$1');
    $routes->post('codes/generer',     'CrudCodeController::generer');

    // ── CRUD Utilisateurs Gold ──────────────────────────────
    $routes->get('gold',               'CrudTypeUtilisateurController::index');
    $routes->post('gold/toggle/(:num)','CrudTypeUtilisateurController::toggle/$1');
});