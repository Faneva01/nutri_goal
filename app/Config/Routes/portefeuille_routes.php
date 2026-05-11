<?php
// ============================================================
// ROUTES PORTEFEUILLE – portefeuille_routes.php
// app/Config/portefeuille_routes.php
// ============================================================

// ── Validation code (front utilisateur) ─────────────────────
$routes->get('portefeuille',               'ValidationCodeController::index');
$routes->post('portefeuille/valider-code', 'ValidationCodeController::valider');

// ── Achat code / paiement ───────────────────────────────────
$routes->get('portefeuille/acheter',       'PaiementController::index');
$routes->post('portefeuille/payer',        'PaiementController::payer');
$routes->get('portefeuille/confirmation',  'PaiementController::confirmation');

// ── Génération code (admin) ─────────────────────────────────
$routes->post('admin/codes/generer',       'Admin\CrudCodeController::generer');