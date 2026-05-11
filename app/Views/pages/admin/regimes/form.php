<?php
// app/Views/pages/admin/regimes/form.php
$this->extend('pages/admin/layouts/admin-main');
$this->section('content');
$isEdit  = ! empty($regime);
$action  = $isEdit
    ? base_url('pages/admin/regimes/update/' . $regime['id'])
    : base_url('pages/admin/regimes/store');
?>

<div class="ad-page-header">
  <div>
    <h1><?= $isEdit ? 'Modifier le régime' : 'Nouveau régime' ?></h1>
    <p><?= $isEdit ? esc($regime['nom']) : 'Remplissez les champs ci-dessous.' ?></p>
  </div>
  <a href="<?= base_url('admin/regimes') ?>" class="ad-btn ad-btn--ghost">← Retour</a>
</div>

<?php if (session()->getFlashdata('error')): ?>
  <div class="ad-toast ad-toast--error" style="margin-bottom:16px;"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="ad-card">
  <form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>

    <div class="ad-form-grid">
      <div class="ad-field">
        <label>Nom du régime</label>
        <input type="text" name="nom" value="<?= esc($regime['nom'] ?? old('nom')) ?>" placeholder="ex : Régime Keto" required>
      </div>

      <div class="ad-field">
        <label>Type</label>
        <select name="type_regime" required>
          <?php foreach (['perte','prise','maintien'] as $t): ?>
            <option value="<?= $t ?>" <?= ($regime['type_regime'] ?? old('type_regime')) === $t ? 'selected' : '' ?>>
              <?= ucfirst($t) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="ad-field">
        <label>Intensité</label>
        <select name="intensite">
          <?php foreach (['legere','moderee','intense'] as $i): ?>
            <option value="<?= $i ?>" <?= ($regime['intensite'] ?? old('intensite')) === $i ? 'selected' : '' ?>>
              <?= ucfirst($i) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="ad-field">
        <label>Variation quotidienne (kg)</label>
        <input type="number" name="variation_quotidienne" step="0.01" min="0"
               value="<?= esc($regime['variation_quotidienne'] ?? old('variation_quotidienne')) ?>" required>
      </div>

      <div class="ad-field">
        <label>Prix par jour (Ar)</label>
        <input type="number" name="prix_jour" step="0.01" min="0"
               value="<?= esc($regime['prix_jour'] ?? old('prix_jour')) ?>" required>
      </div>

      <?php if ($isEdit): ?>
        <div class="ad-field">
          <label>Statut</label>
          <select name="actif">
            <option value="1" <?= ($regime['actif'] ?? 1) ? 'selected' : '' ?>>Actif</option>
            <option value="0" <?= !($regime['actif'] ?? 1) ? 'selected' : '' ?>>Inactif</option>
          </select>
        </div>
      <?php endif; ?>
    </div>

    <!-- % Viande / Poisson / Volaille -->
    <p style="font-size:13px;font-weight:700;color:#2d2d2d;margin:8px 0 12px;">
      Composition protéinée <small style="font-weight:500;color:#999;">(total doit faire 100 %)</small>
    </p>
    <div class="ad-form-grid" style="grid-template-columns:1fr 1fr 1fr;">
      <div class="ad-field">
        <label>% Viande</label>
        <input type="number" name="pourcentage_viande" id="pViande" min="0" max="100"
               value="<?= esc($regime['pourcentage_viande'] ?? old('pourcentage_viande', 33)) ?>"
               oninput="updateTotal()" required>
      </div>
      <div class="ad-field">
        <label>% Poisson</label>
        <input type="number" name="pourcentage_poisson" id="pPoisson" min="0" max="100"
               value="<?= esc($regime['pourcentage_poisson'] ?? old('pourcentage_poisson', 34)) ?>"
               oninput="updateTotal()" required>
      </div>
      <div class="ad-field">
        <label>% Volaille</label>
        <input type="number" name="pourcentage_volaille" id="pVolaille" min="0" max="100"
               value="<?= esc($regime['pourcentage_volaille'] ?? old('pourcentage_volaille', 33)) ?>"
               oninput="updateTotal()" required>
      </div>
    </div>
    <p id="pct-total" style="font-size:13px;font-weight:700;margin-bottom:16px;"></p>

    <div class="ad-field">
      <label>Description</label>
      <textarea name="description" placeholder="Description du régime..."><?= esc($regime['description'] ?? old('description')) ?></textarea>
    </div>

    <div style="text-align:right;margin-top:8px;">
      <button type="submit" class="ad-btn ad-btn--primary">
        <?= $isEdit ? '💾 Enregistrer les modifications' : '➕ Créer le régime' ?>
      </button>
    </div>
  </form>
</div>

<script>
  function updateTotal() {
    const v  = parseInt(document.getElementById('pViande').value)   || 0;
    const p  = parseInt(document.getElementById('pPoisson').value)  || 0;
    const vo = parseInt(document.getElementById('pVolaille').value) || 0;
    const total  = v + p + vo;
    const el     = document.getElementById('pct-total');
    el.textContent = `Total : ${total} %`;
    el.style.color = total === 100 ? '#2e7d32' : '#c0392b';
  }
  updateTotal();
</script>

<?php $this->endSection(); ?>
