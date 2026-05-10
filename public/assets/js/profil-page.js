/* ============================================================
   NutriGoal – script.js
   ============================================================ */

// ── Profile Photo Upload ─────────────────────────────────────
function handlePhotoUpload(event) {
  const file = event.target.files[0];
  if (!file) return;

  if (!file.type.startsWith('image/')) {
    showToast('⚠️ Veuillez sélectionner une image valide.');
    return;
  }

  const reader = new FileReader();
  reader.onload = function(e) {
    const dataUrl = e.target.result;

    // Update banner avatar
    const avatarCircle = document.querySelector('.avatar-circle');
    avatarCircle.innerHTML = `<img src="${dataUrl}" alt="Photo de profil" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />`;

    // Update navbar avatar
    const navAvatar = document.getElementById('nav-avatar');
    navAvatar.innerHTML = `<img src="${dataUrl}" alt="Photo de profil" />`;

    showToast('✅ Photo de profil mise à jour !');
  };
  reader.readAsDataURL(file);

  // Reset input so same file can be re-selected
  event.target.value = '';
}


function updateIMC() {
  const taille = parseFloat(document.getElementById('taille').value) / 100;
  const poids  = parseFloat(document.getElementById('poids').value);

  if (!taille || !poids || taille <= 0) return;

  const imc = poids / (taille * taille);
  const rounded = imc.toFixed(2);

  document.getElementById('imc-display').value = rounded;
  document.getElementById('imc-value').textContent  = rounded;

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

// ── Spinner Helpers ──────────────────────────────────────────
function increment(id, step = 1) {
  const el = document.getElementById(id);
  const max = parseFloat(el.max) || Infinity;
  const val = parseFloat(el.value) + step;
  el.value = Math.min(val, max).toFixed(step < 1 ? 1 : 0);
  updateIMC();
}

function decrement(id, step = 1) {
  const el = document.getElementById(id);
  const min = parseFloat(el.min) || 0;
  const val = parseFloat(el.value) - step;
  el.value = Math.max(val, min).toFixed(step < 1 ? 1 : 0);
  updateIMC();
}

// ── Password Toggle ──────────────────────────────────────────
function togglePassword() {
  const pwd  = document.getElementById('password');
  const icon = document.getElementById('eye-icon');
  const show = pwd.type === 'password';
  pwd.type = show ? 'text' : 'password';

  icon.innerHTML = show
    ? `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
       <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
       <line x1="1" y1="1" x2="23" y2="23"/>`
    : `<path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12z"/>
       <circle cx="12" cy="12" r="3"/>`;
}

// ── Save Changes ─────────────────────────────────────────────
function saveChanges() {
  const name  = document.getElementById('fullname').value.trim();
  const email = document.getElementById('email').value.trim();

  if (!name || !email) {
    showToast('⚠️ Veuillez remplir tous les champs obligatoires.');
    return;
  }

  // Update navbar username
  document.getElementById('navbar-username').textContent = name.split(' ')[0];

  // Update banner
  document.getElementById('banner-name').textContent  = name;
  document.getElementById('banner-email').textContent = email;

  // Update avatar initial (only if no photo uploaded)
  const avatarEl = document.querySelector('.avatar-circle');
  if (!avatarEl.querySelector('img')) {
    avatarEl.textContent = name.charAt(0).toUpperCase();
  }

  showToast('✅ Modifications enregistrées avec succès !');
}

// ── Gold Toggle ──────────────────────────────────────────────
function toggleGold() {
  const active = document.getElementById('gold-toggle').checked;
  document.getElementById('gold-status-text').textContent = active ? 'Activé' : 'Non activé';
  showToast(active ? '👑 Option Gold activée !' : '🔕 Option Gold désactivée.');
}

// ── Wallet Code Validation ────────────────────────────────────
const PROMO_CODES = { 'NUTRIFIT10': 10, 'GOLD25': 25, 'BONUS5': 5 };
const usedCodes   = new Set();

function validateCode() {
  const input    = document.getElementById('promo-code');
  const feedback = document.getElementById('code-feedback');
  const code     = input.value.trim().toUpperCase();

  if (!code) {
    feedback.textContent = 'Veuillez entrer un code.';
    feedback.className   = 'code-feedback error';
    return;
  }

  if (usedCodes.has(code)) {
    feedback.textContent = 'Ce code a déjà été utilisé.';
    feedback.className   = 'code-feedback error';
    return;
  }

  if (PROMO_CODES[code] !== undefined) {
    const amount = PROMO_CODES[code];
    usedCodes.add(code);

    const balanceEl   = document.getElementById('balance');
    const current     = parseFloat(balanceEl.textContent.replace(' €', '').replace(',', '.'));
    const newBalance  = (current + amount).toFixed(2);
    balanceEl.textContent = newBalance + ' €';

    feedback.textContent = `+${amount} € crédités sur votre solde !`;
    feedback.className   = 'code-feedback success';
    input.value          = '';

    // Animate balance
    balanceEl.style.transform = 'scale(1.15)';
    setTimeout(() => balanceEl.style.transform = 'scale(1)', 300);

    showToast(`💰 Code valide ! +${amount} € ajoutés.`);
  } else {
    feedback.textContent = 'Code invalide ou expiré.';
    feedback.className   = 'code-feedback error';
  }

  setTimeout(() => { feedback.textContent = ''; feedback.className = 'code-feedback'; }, 4000);
}

// ── Toast Helper ─────────────────────────────────────────────
function showToast(message) {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.classList.add('show');
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 3000);
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  updateIMC();

  // Allow Enter key on promo code input
  document.getElementById('promo-code').addEventListener('keydown', e => {
    if (e.key === 'Enter') validateCode();
  });
});
