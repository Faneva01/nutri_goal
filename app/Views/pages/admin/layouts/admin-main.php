<!DOCTYPE html>
<!-- app/Views/admin/layouts/admin-main.php -->
<?php
$adminNom  = session()->get('admin_nom') ?? 'Admin';
$adminRole = session()->get('admin_role') ?? 'admin';
$pageTitle = $title ?? 'Admin – NutriGoal';
$pageStyles  = $styles  ?? [];
$pageScripts = $scripts ?? [];
?>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($pageTitle) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin/admin-layout.css') ?>">
  <?php foreach ($pageStyles as $s): ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/' . $s) ?>">
  <?php endforeach; ?>
</head>
<body class="ad-body">

<!-- ── SIDEBAR ────────────────────────────────────────────── -->
<aside class="ad-sidebar" id="sidebar">
  <div class="ad-sidebar__logo">
    <div class="ad-sidebar__logo-icon">🥗</div>
    <span class="ad-sidebar__logo-text">NutriGoal</span>
  </div>

  <nav class="ad-nav">
    <p class="ad-nav__section">Principal</p>
    <a href="<?= base_url('admin/dashboard') ?>" class="ad-nav__link <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
      Tableau de bord
    </a>

    <p class="ad-nav__section">Gestion</p>
    <a href="<?= base_url('admin/regimes') ?>" class="ad-nav__link <?= strpos(uri_string(),'admin/regimes') !== false ? 'active' : '' ?>">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
      Régimes
    </a>
    <a href="<?= base_url('admin/activites') ?>" class="ad-nav__link <?= strpos(uri_string(),'admin/activites') !== false ? 'active' : '' ?>">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      Activités sportives
    </a>
    <a href="<?= base_url('admin/codes') ?>" class="ad-nav__link <?= strpos(uri_string(),'admin/codes') !== false ? 'active' : '' ?>">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
      Codes portefeuille
    </a>
    <a href="<?= base_url('admin/gold') ?>" class="ad-nav__link <?= strpos(uri_string(),'admin/gold') !== false ? 'active' : '' ?>">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
      Utilisateurs Gold
    </a>

    <p class="ad-nav__section">Compte</p>
    <a href="<?= base_url('admin/logout') ?>" class="ad-nav__link ad-nav__link--danger">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Déconnexion
    </a>
  </nav>

  <div class="ad-sidebar__user">
    <div class="ad-sidebar__avatar"><?= esc(strtoupper(substr($adminNom, 0, 1))) ?></div>
    <div>
      <p class="ad-sidebar__name"><?= esc($adminNom) ?></p>
      <p class="ad-sidebar__role"><?= esc($adminRole) ?></p>
    </div>
  </div>
</aside>

<!-- ── TOGGLE MOBILE ─────────────────────────────────────── -->
<button class="ad-toggle" id="sidebarToggle" aria-label="Menu">
  <span></span><span></span><span></span>
</button>

<!-- ── MAIN CONTENT ──────────────────────────────────────── -->
<main class="ad-main" id="mainContent">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="ad-toast ad-toast--success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="ad-toast ad-toast--error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <?= $this->renderSection('content') ?>
</main>

<script>
  const sidebar = document.getElementById('sidebar');
  const btn     = document.getElementById('sidebarToggle');
  btn.addEventListener('click', () => sidebar.classList.toggle('open'));
</script>
<?php foreach ($pageScripts as $s): ?>
  <script src="<?= base_url('assets/js/' . $s) ?>"></script>
<?php endforeach; ?>
</body>
</html>
