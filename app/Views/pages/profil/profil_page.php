<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<input type="file" id="photo-input" accept="image/*" style="display:none" onchange="handlePhotoUpload(event)" />

<div class="profil-wrap">

  <div class="profile-banner">
    <div class="container">
      <div class="avatar-wrapper">
        <div class="avatar-circle"><?= esc(mb_strtoupper(mb_substr($user['nom_complet'] ?? 'U', 0, 1))) ?></div>
        <button class="camera-btn" title="Changer la photo"
                onclick="document.getElementById('photo-input').click()" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
               viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
  </div>

  <!-- ═══════════════════════════════════════════════════════
       BODY
  ════════════════════════════════════════════════════════ -->
  <div class="container">
    <div class="profil-body">

      <!-- ─── COLONNE PRINCIPALE ──────────────────────────── -->
      <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- INFOS PERSONNELLES -->
        <article class="dash-panel">
          <div class="panel-header">
            <div>
              <h2><i class="fas fa-user"></i> Informations personnelles</h2>
              <p class="sub">Modifiez vos données puis enregistrez.</p>
            </div>
          </div>

          <div class="form-group">
            <label for="fullname">Nom complet</label>
            <input type="text" id="fullname" value="<?= esc($user['nom_complet'] ?? '') ?>" />
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
                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
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
                  <option value="F"     <?= ($user['genre'] ?? '') === 'F'     ? 'selected' : '' ?>>Femme</option>
                  <option value="M"     <?= ($user['genre'] ?? '') === 'M'     ? 'selected' : '' ?>>Homme</option>
                  <option value="Autre" <?= ($user['genre'] ?? '') === 'Autre' ? 'selected' : '' ?>>Autre</option>
                </select>
                <svg class="select-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"/>
                </svg>
              </div>
            </div>
            <div class="form-group">
              <label>IMC (calculé auto)</label>
              <input type="text" id="imc-display" value="<?= esc($user['imc'] ?? '0') ?>"
                     readonly class="imc-input" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="taille">Taille (cm)</label>
              <div class="spinner-wrapper">
                <input type="number" id="taille" value="<?= esc($user['taille'] ?? 170) ?>"
                       min="50" max="250" oninput="updateIMC()" />
                <div class="spinner-btns">
                  <button type="button" onclick="increment('taille',1)">▲</button>
                  <button type="button" onclick="decrement('taille',1)">▼</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="poids">Poids (kg)</label>
              <div class="spinner-wrapper">
                <input type="number" id="poids" value="<?= esc($user['poids'] ?? 70) ?>"
                       min="20" max="300" step="0.1" oninput="updateIMC()" />
                <div class="spinner-btns">
                  <button type="button" onclick="increment('poids',0.5)">▲</button>
                  <button type="button" onclick="decrement('poids',0.5)">▼</button>
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
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
              </svg>
              Enregistrer les modifications
            </button>
          </div>
        </article>

        <!-- OBJECTIFS -->
        <article class="dash-panel">
          <div class="panel-header">
            <div>
              <h2><i class="fas fa-bullseye"></i> Mes objectifs</h2>
              <p class="sub">Suivi de vos objectifs nutritionnels</p>
            </div>
          </div>
          <div class="objectif-card">
            <p class="objectif-title">Récapitulatif</p>
            <div class="objectif-row">
              <span>Objectif principal</span>
              <span><?= esc($user['objectif_principal'] ?? 'Non défini') ?></span>
            </div>
            <div class="objectif-row">
              <span>Cible</span>
              <span><?= esc($user['objectif_cible'] ?? '—') ?></span>
            </div>
            <div class="objectif-row">
              <span>Dernier suivi</span>
              <span><?= esc($user['dernier_poids'] ?? '—') ?></span>
            </div>
          </div>
        </article>

      </div><!-- /colonne principale -->

      <!-- ─── SIDEBAR ────────────────────────────────────── -->
      <aside class="profil-sidebar">

        <!-- OPTION GOLD -->
        <div class="gold-card">
          <div class="gold-header-top">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                 viewBox="0 0 24 24" stroke="white" stroke-width="2">
              <path d="M5 3l3 6 4-8 4 8 3-6 2 9H3l2-9z"/>
            </svg>
            Option Gold
          </div>
          <p class="gold-subtitle">−15 % sur tous les régimes</p>
          <div class="gold-status">
            <div class="status-text">
              <span class="status-label">Statut</span>
              <span class="status-value" id="gold-status-text">
                <?= (int)($user['option_gold'] ?? 0) === 1 ? 'Activé' : 'Non activé' ?>
              </span>
            </div>
            <label class="toggle">
              <input type="checkbox" id="gold-toggle" onchange="toggleGold()"
                     <?= (int)($user['option_gold'] ?? 0) === 1 ? 'checked' : '' ?> />
              <span class="slider"></span>
            </label>
          </div>
        </div>

        <!-- PORTE-MONNAIE -->
        <div class="wallet-card">
          <div class="wallet-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none"
                 viewBox="0 0 24 24" stroke="#E17864" stroke-width="2">
              <rect x="1" y="6" width="22" height="15" rx="2"/>
              <path d="M16 12h2"/><path d="M1 10h22"/>
              <path d="M5 6V4a1 1 0 011-1h12a1 1 0 011 1v2"/>
            </svg>
            <span class="wallet-title">Porte-monnaie</span>
          </div>
          <hr class="divider" />
          <div class="balance-section">
            <span class="balance-label">Solde actuel</span>
            <span class="balance-amount" id="balance"><?= number_format((float)($user['solde'] ?? 0), 2) ?> €</span>
          </div>
          <hr class="divider" />
          <div class="recharge-section">
            <span class="recharge-label">Recharger avec un code</span>
            <div class="recharge-row">
              <input type="text" id="promo-code" placeholder="ex : PROMO10" class="code-input" />
              <button class="valider-btn" onclick="validateCode()" type="button">Valider</button>
            </div>
            <span class="code-feedback" id="code-feedback"></span>
          </div>
        </div>

        <!-- CALENDRIER -->
        <div class="insight-card">
          <div class="insight-card__head">
            <h3 class="insight-card__title">
              <i class="fas fa-calendar-alt" style="color:#E17864;margin-right:6px;"></i>
              Prochain rappel
            </h3>
          </div>
          <div class="insight-card__body">
            <p class="insight-card__label">Planifié</p>
            <p class="insight-card__highlight"><?= esc($prochainRappel ?? 'Aucun rappel programmé') ?></p>
          </div>
        </div>

        <!-- HISTORIQUE ACTIVITÉ -->
        <div class="insight-card">
          <div class="insight-card__head">
            <h3 class="insight-card__title">
              <i class="fas fa-history" style="color:#E17864;margin-right:6px;"></i>
              Historique d'activité
            </h3>
          </div>
          <div class="insight-card__body">
            <?php if (!empty($historiqueActivites) && is_array($historiqueActivites)): ?>
              <ul class="activity-list" role="list">
                <?php foreach ($historiqueActivites as $item): ?>
                  <li class="activity-item">
                    <div class="activity-item__text">
                      <time class="activity-item__date" datetime="<?= esc($item['date'] ?? '') ?>">
                        <?= esc($item['date'] ?? '—') ?>
                      </time>
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

    </div><!-- /profil-body -->
  </div><!-- /container -->

</div><!-- /profil-wrap -->

<!-- TOAST -->
<div class="toast" id="toast"></div>

<?= $this->endSection() ?>