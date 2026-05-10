/* ============================================================
   profil-page.js  — NutriGoal
   ============================================================ */

// ── Photo Upload ─────────────────────────────────────────────
function handlePhotoUpload(event) {
  const file = event.target.files[0];
  if (!file) return;
  if (!file.type.startsWith('image/')) {
    showToast('warning', 'Veuillez sélectionner une image valide.');
    return;
  }
  const reader = new FileReader();
  reader.onload = (e) => {
    const src = e.target.result;
    document.querySelector('.avatar-circle').innerHTML =
      `<img src="${src}" alt="Photo" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />`;
    showToast('success', 'Photo mise à jour localement.');
  };
  reader.readAsDataURL(file);
  event.target.value = '';
}

// ── IMC ──────────────────────────────────────────────────────
function updateIMC() {
  const taille = parseFloat(document.getElementById('taille').value) / 100;
  const poids  = parseFloat(document.getElementById('poids').value);
  if (!taille || !poids || taille <= 0) return;

  const imc     = poids / (taille * taille);
  const rounded = imc.toFixed(2);

  document.getElementById('imc-display').value    = rounded;
  document.getElementById('imc-value').textContent = rounded;

  const badge = document.getElementById('imc-badge');
  badge.className = 'imc-badge';

  if (imc < 18.5) {
    badge.textContent = 'Insuffisance pondérale';
    badge.classList.add('maigre');
  } else if (imc < 25) {
    badge.textContent = 'Corpulence normale';
  } else if (imc < 30) {
    badge.textContent = 'Surpoids';
    badge.classList.add('surpoids');
  } else {
    badge.textContent = 'Obésité';
    badge.classList.add('obesite');
  }
}

// ── Spinners ─────────────────────────────────────────────────
function increment(id, step = 1) {
  const el  = document.getElementById(id);
  const max = parseFloat(el.max) || Infinity;
  el.value  = Math.min(parseFloat(el.value) + step, max).toFixed(step < 1 ? 1 : 0);
  updateIMC();
}

function decrement(id, step = 1) {
  const el  = document.getElementById(id);
  const min = parseFloat(el.min) || 0;
  el.value  = Math.max(parseFloat(el.value) - step, min).toFixed(step < 1 ? 1 : 0);
  updateIMC();
}

// ── Password Toggle ──────────────────────────────────────────
function togglePassword() {
  const pwd  = document.getElementById('password');
  const icon = document.getElementById('eye-icon');
  const show = pwd.type === 'password';
  pwd.type   = show ? 'text' : 'password';
  icon.innerHTML = show
    ? `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
       <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
       <line x1="1" y1="1" x2="23" y2="23"/>`
    : `<path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/>
       <circle cx="12" cy="12" r="3"/>`;
}

// ── Save Changes (appel AJAX → ProfilController::update) ─────
async function saveChanges() {
  const name  = document.getElementById('fullname').value.trim();
  const email = document.getElementById('email').value.trim();

  if (!name || !email) {
    showToast('warning', 'Nom et email sont obligatoires.');
    return;
  }

  const body = new URLSearchParams({
    fullname: name,
    email,
    genre:    document.getElementById('genre').value,
    taille:   document.getElementById('taille').value,
    poids:    document.getElementById('poids').value,
    password: document.getElementById('password').value,
  });

  const btn = document.querySelector('.save-btn');
  btn.disabled  = true;
  btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Enregistrement…`;

  try {
    const res  = await fetch('/profil/update', { method: 'POST', body });
    const data = await res.json();

    if (data.success) {
      document.getElementById('banner-name').textContent  = name;
      document.getElementById('banner-email').textContent = email;
      const avatarEl = document.querySelector('.avatar-circle');
      if (!avatarEl.querySelector('img')) {
        avatarEl.textContent = name.charAt(0).toUpperCase();
      }
      if (data.imc) {
        document.getElementById('imc-display').value     = data.imc;
        document.getElementById('imc-value').textContent = data.imc;
      }
      document.getElementById('password').value = '';
      showToast('success', data.message);
    } else {
      showToast('error', data.message || 'Erreur inconnue.');
    }
  } catch {
    showToast('error', 'Erreur réseau. Veuillez réessayer.');
  } finally {
    btn.disabled  = false;
    btn.innerHTML = `<i class="fas fa-save"></i> Enregistrer les modifications`;
  }
}

// ── Gold Toggle (appel AJAX → ProfilController::toggleGold) ──
async function toggleGold() {
  const active = document.getElementById('gold-toggle').checked;
  document.getElementById('gold-status-text').textContent = active ? 'Activé' : 'Non activé';

  try {
    const body = new URLSearchParams({ option_gold: active ? 1 : 0 });
    const res  = await fetch('/profil/toggleGold', { method: 'POST', body });
    const data = await res.json();
    if (!data.success) showToast('error', 'Erreur lors de la mise à jour Gold.');
    else showToast(active ? 'gold' : 'info', active ? 'Option Gold activée !' : 'Option Gold désactivée.');
  } catch {
    showToast('error', 'Erreur réseau.');
  }
}

// ── Wallet Code (appel AJAX → ProfilController::rechargerSolde) ──
async function validateCode() {
  const input    = document.getElementById('promo-code');
  const feedback = document.getElementById('code-feedback');
  const code     = input.value.trim();

  if (!code) {
    feedback.textContent = 'Veuillez entrer un code.';
    feedback.className   = 'code-feedback error';
    return;
  }

  try {
    const body = new URLSearchParams({ code });
    const res  = await fetch('/profil/rechargerSolde', { method: 'POST', body });
    const data = await res.json();

    if (data.success) {
      const balanceEl     = document.getElementById('balance');
      balanceEl.textContent = data.nouveau_solde + ' €';
      balanceEl.style.transform = 'scale(1.15)';
      setTimeout(() => balanceEl.style.transform = 'scale(1)', 300);

      feedback.textContent = data.message;
      feedback.className   = 'code-feedback success';
      input.value          = '';
      showToast('success', data.message);
    } else {
      feedback.textContent = data.message || 'Code invalide.';
      feedback.className   = 'code-feedback error';
    }
  } catch {
    feedback.textContent = 'Erreur réseau.';
    feedback.className   = 'code-feedback error';
  }

  setTimeout(() => {
    feedback.textContent = '';
    feedback.className   = 'code-feedback';
  }, 4000);
}

// ── Toast ─────────────────────────────────────────────────────
const TOAST_ICONS = {
  success: '<i class="fas fa-check-circle"></i>',
  error:   '<i class="fas fa-times-circle"></i>',
  warning: '<i class="fas fa-exclamation-triangle"></i>',
  info:    '<i class="fas fa-info-circle"></i>',
  gold:    '<i class="fas fa-crown"></i>',
};

function showToast(type, message) {
  const toast = document.getElementById('toast');
  const icon  = TOAST_ICONS[type] || TOAST_ICONS.info;
  toast.innerHTML = `${icon} <span>${message}</span>`;
  toast.className = `toast toast--${type} show`;
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 3000);
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  updateIMC();
  document.getElementById('promo-code').addEventListener('keydown', e => {
    if (e.key === 'Enter') validateCode();
  });
});