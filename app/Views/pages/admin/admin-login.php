<!DOCTYPE html>
<!-- app/Views/admin/admin-login.php -->
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Admin – NutriGoal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin/admin-login.css') ?>">
</head>
<body class="al-body">

  <div class="al-card">
    <!-- Logo -->
    <div class="al-logo">
      <div class="al-logo-icon">🥗</div>
      <span class="al-logo-text">NutriGoal</span>
    </div>

    <h1 class="al-title">Back Office</h1>
    <p class="al-sub">Connectez-vous pour accéder au panneau d'administration.</p>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="al-alert">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/login') ?>">
      <?= csrf_field() ?>

      <div class="al-field">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email"
               placeholder="admin@nutri-goal.com"
               value="<?= esc(old('email')) ?>" required>
      </div>

      <div class="al-field">
        <label for="mot_de_passe">Mot de passe</label>
        <div class="al-eye-wrap">
          <input type="password" id="mot_de_passe" name="mot_de_passe"
                 placeholder="••••••••" required>
          <button type="button" class="al-eye" onclick="togglePwd(this)">
            <svg id="eye-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <button type="submit" class="al-btn">Se connecter</button>
    </form>

    <p class="al-back">
      <a href="<?= base_url('/') ?>">← Retour au site</a>
    </p>
  </div>

  <script>
    function togglePwd(btn) {
      const inp = btn.previousElementSibling;
      inp.type = inp.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
