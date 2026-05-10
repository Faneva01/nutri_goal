<?php
$fullName = $user['nom_complet'] ?? 'Utilisateur';
$firstName = trim(explode(' ', $fullName)[0] ?? 'Utilisateur');
?>

<nav class="navbar">
    <div class="navbar-left">
        <div class="logo">
            <div class="logo-icon">🥗</div>
            <span class="logo-text">NutriGoal</span>
        </div>
    </div>
    <div class="navbar-right">
        <a href="<?= base_url('dashboard') ?>" class="nav-link">Tableau de bord</a>
        <a href="#" class="nav-link">Régimes</a>
        <a href="#" class="nav-link">Activités</a>
        <a href="<?= base_url('profil') ?>" class="avatar-btn" id="navbar-avatar-btn" title="Mon profil">
            <div class="nav-avatar" id="nav-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.58-7 8-7s8 3 8 7"/>
                </svg>
            </div>
            <span id="navbar-username"><?= esc($firstName) ?></span>
        </a>
    </div>
</nav>
