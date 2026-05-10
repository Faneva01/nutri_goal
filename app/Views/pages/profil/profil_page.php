<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

  <!-- Hidden file input for profile photo -->
  <input type="file" id="photo-input" accept="image/*" style="display:none" onchange="handlePhotoUpload(event)" />

  <!-- PROFILE BANNER -->
  <div class="profile-banner">
    <div class="avatar-wrapper">
      <div class="avatar-circle">A</div>
      <button class="camera-btn" title="Changer la photo" onclick="document.getElementById('photo-input').click()" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
          <circle cx="12" cy="13" r="4"/>
        </svg>
      </button>
    </div>
    <div class="banner-info">
      <h2 id="banner-name"><?= esc($user['nom_complet'] ?? 'Utilisateur') ?></h2>
      <p id="banner-email"><?= esc($user['email'] ?? '') ?></p>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <!-- LEFT: PERSONAL INFO FORM -->
    <section class="card form-card">
      <div class="card-header">
        <h3>Informations personnelles</h3>
        <p>Modifiez vos données puis enregistrez.</p>
      </div>

      <div class="form-group">
        <label for="fullname">Nom complet</label>
        <input type="text" id="fullname" value="<?= esc($user['nom_complet'] ?? 'Utilisateur') ?>" />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" value="<?= esc($user['email'] ?? '') ?>" />
      </div>

      <div class="form-group">
        <label for="password">Nouveau mot de passe</label>
        <div class="input-eye-wrapper">
          <input type="password" id="password" placeholder="Laisser vide pour ne pas changer" />
          <button class="eye-btn" onclick="togglePassword()" type="button" title="Afficher/Masquer">
            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="genre">Genre</label>
          <div class="select-wrapper">
            <select id="genre">
              <option value="F" <?= (($user['genre'] ?? '') === 'F') ? 'selected' : '' ?>>Femme</option>
              <option value="M" <?= (($user['genre'] ?? '') === 'M') ? 'selected' : '' ?>>Homme</option>
              <option value="Autre" <?= (($user['genre'] ?? '') === 'Autre') ? 'selected' : '' ?>>Autre</option>
            </select>
            <svg class="select-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M6 9l6 6 6-6"/>
            </svg>
          </div>
        </div>
        <div class="form-group">
          <label>IMC (calculé)</label>
          <input type="text" id="imc-display" value="<?= esc($user['imc'] ?? '0') ?>" readonly class="imc-input" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="taille">Taille (cm)</label>
          <div class="spinner-wrapper">
            <input type="number" id="taille" value="<?= esc($user['taille'] ?? '170') ?>" min="100" max="250" oninput="updateIMC()" />
            <div class="spinner-btns">
              <button type="button" onclick="increment('taille', 1)">▲</button>
              <button type="button" onclick="decrement('taille', 1)">▼</button>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="poids">Poids (kg)</label>
          <div class="spinner-wrapper">
            <input type="number" id="poids" value="<?= esc($user['poids'] ?? '70') ?>" min="30" max="300" step="0.1" oninput="updateIMC()" />
            <div class="spinner-btns">
              <button type="button" onclick="increment('poids', 0.5)">▲</button>
              <button type="button" onclick="decrement('poids', 0.5)">▼</button>
            </div>
          </div>
        </div>
      </div>

      <!-- IMC RESULT -->
      <div class="imc-card">
        <div class="imc-info">
          <span class="imc-label">Indice de masse corporelle</span>
          <span class="imc-value" id="imc-value"><?= esc($user['imc'] ?? '0') ?></span>
        </div>
        <span class="imc-badge" id="imc-badge">Corpulence normale</span>
      </div>

      <div class="form-actions">
        <button class="save-btn" onclick="saveChanges()" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
            <polyline points="7 3 7 8 15 8"/>
          </svg>
          Enregistrer les modifications
        </button>
      </div>

      <!-- OBJECTIFS -->
      <div class="card wallet-card" style="margin-top: 16px;">
        <div class="wallet-header">
          <span class="wallet-title">Objectif utilisateur</span>
        </div>
        <hr class="divider" />
        <div class="balance-section">
          <span class="balance-label">Objectif principal</span>
          <span class="balance-amount"><?= esc($user['objectif_principal'] ?? 'Non defini') ?></span>
        </div>
        <div class="balance-section">
          <span class="balance-label">Cible</span>
          <span class="balance-amount"><?= esc($user['objectif_cible'] ?? '-') ?></span>
        </div>
        <div class="balance-section">
          <span class="balance-label">Dernier suivi</span>
          <span class="balance-amount"><?= esc($user['dernier_poids'] ?? '-') ?></span>
        </div>
      </div>
    </section>

    <!-- RIGHT SIDEBAR -->
    <aside class="sidebar">

      <!-- OPTION GOLD -->
      <div class="gold-card">
        <div class="gold-header-top">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
            <path d="M5 3l3 6 4-8 4 8 3-6 2 9H3l2-9z"/>
          </svg>
          <span>Option Gold</span>
        </div>
        <p class="gold-subtitle">-15% sur tous les régimes</p>
        <div class="gold-status">
          <div class="status-text">
            <span class="status-label">Statut</span>
            <span class="status-value" id="gold-status-text"><?= (int) ($user['option_gold'] ?? 0) === 1 ? 'Activé' : 'Non activé' ?></span>
          </div>
          <label class="toggle">
            <input type="checkbox" id="gold-toggle" onchange="toggleGold()" <?= (int) ($user['option_gold'] ?? 0) === 1 ? 'checked' : '' ?> />
            <span class="slider"></span>
          </label>
        </div>
      </div>

      <!-- PORTE-MONNAIE -->
      <div class="card wallet-card">
        <div class="wallet-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#e07050" stroke-width="2">
            <rect x="1" y="6" width="22" height="15" rx="2"/>
            <path d="M16 12h2"/>
            <path d="M1 10h22"/>
            <path d="M5 6V4a1 1 0 011-1h12a1 1 0 011 1v2"/>
          </svg>
          <span class="wallet-title">Porte-monnaie</span>
        </div>
        <hr class="divider" />
        <div class="balance-section">
          <span class="balance-label">Solde actuel</span>
          <span class="balance-amount" id="balance"><?= number_format((float) ($user['solde'] ?? 0), 2) ?> €</span>
        </div>
        <hr class="divider" />
        <div class="recharge-section">
          <label class="recharge-label">Recharger avec un code</label>
          <div class="recharge-row">
            <input type="text" id="promo-code" placeholder="ABC-123" class="code-input" />
            <button class="valider-btn" onclick="validateCode()" type="button">Valider</button>
          </div>
          <span class="code-feedback" id="code-feedback"></span>
        </div>
      </div>

      <!-- CALENDRIER -->
      <div class="card insight-card">
        <div class="insight-card__head">
          <h3 class="insight-card__title">Calendrier utilisateur</h3>
        </div>
        <div class="insight-card__body">
          <p class="insight-card__label">Prochain rappel</p>
          <p class="insight-card__highlight"><?= esc($prochainRappel ?? 'Aucun rappel programmé') ?></p>
        </div>
      </div>

      <!-- HISTORIQUE ACTIVITÉ -->
      <div class="card insight-card">
        <div class="insight-card__head">
          <h3 class="insight-card__title">Historique d'activité</h3>
        </div>
        <div class="insight-card__body">
          <?php if (!empty($historiqueActivites) && is_array($historiqueActivites)): ?>
            <ul class="activity-list" role="list">
              <?php foreach ($historiqueActivites as $item): ?>
                <li class="activity-item">
                  <div class="activity-item__text">
                    <time class="activity-item__date" datetime="<?= esc($item['date'] ?? '') ?>"><?= esc($item['date'] ?? '—') ?></time>
                    <span class="activity-item__name"><?= esc($item['label'] ?? '—') ?></span>
                  </div>
                  <span class="activity-item__metric"><?= esc($item['valeur'] ?? '—') ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="insight-card__empty">Aucune activité enregistrée.</p>
          <?php endif; ?>
        </div>
      </div>

    </aside>
  </main>

  <!-- TOAST NOTIFICATION -->
  <div class="toast" id="toast"></div>
<?= $this->endSection() ?>