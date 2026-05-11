// public/assets/js/admin/dashboard-admin.js
'use strict';

const COLORS = {
  primary:   '#f07050',
  light:     '#f8a080',
  pale:      '#fde8e0',
  gold:      '#FAB863',
  green:     '#6A7C4F',
  yellow:    '#DBEB94',
  dark:      '#3d3833',
  grey:      '#c8c0bc',
};

const parse = id => JSON.parse(document.getElementById(id)?.textContent || '[]');

Chart.defaults.font.family = "'Nunito', sans-serif";
Chart.defaults.color       = '#8f8983';

// ── 1. Inscriptions (bar) ────────────────────────────────────
(function () {
  const raw  = parse('data-inscriptions');
  const ctx  = document.getElementById('chartInscriptions');
  if (!ctx || !raw.length) return;
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels:   raw.map(r => r.mois),
      datasets: [{
        label:           'Inscriptions',
        data:            raw.map(r => r.total),
        backgroundColor: COLORS.pale,
        borderColor:     COLORS.primary,
        borderWidth:     2,
        borderRadius:    8,
      }],
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales:  { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
    },
  });
})();

// ── 2. Types utilisateurs (doughnut) ────────────────────────
(function () {
  const raw = parse('data-types');
  const ctx = document.getElementById('chartTypes');
  if (!ctx) return;
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels:   ['Gold ⭐', 'Standard'],
      datasets: [{
        data:            [raw.gold, raw.simple],
        backgroundColor: [COLORS.gold, COLORS.grey],
        borderWidth:     0,
        hoverOffset:     6,
      }],
    },
    options: {
      cutout:  '68%',
      plugins: {
        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 16 } },
      },
    },
  });
})();

// ── 3. CA mensuel (bar groupé) ───────────────────────────────
(function () {
  const raw = parse('data-ca');
  const ctx = document.getElementById('chartCA');
  if (!ctx || !raw.length) return;
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: raw.map(r => r.mois),
      datasets: [
        {
          label:           'Régimes',
          data:            raw.map(r => r.regimes),
          backgroundColor: COLORS.primary,
          borderRadius:    6,
        },
        {
          label:           'Gold',
          data:            raw.map(r => r.gold),
          backgroundColor: COLORS.gold,
          borderRadius:    6,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top' } },
      scales:  { y: { beginAtZero: true } },
    },
  });
})();

// ── 4. Régimes populaires (horizontal bar) ───────────────────
(function () {
  const raw = parse('data-regimes');
  const ctx = document.getElementById('chartRegimes');
  if (!ctx || !raw.length) return;
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: raw.map(r => r.nom),
      datasets: [{
        label:           'Abonnés',
        data:            raw.map(r => r.abonnes),
        backgroundColor: [
          COLORS.primary, COLORS.light, COLORS.gold,
          COLORS.green,   COLORS.yellow, COLORS.pale, COLORS.grey,
        ],
        borderRadius: 6,
        borderWidth:  0,
      }],
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      plugins: { legend: { display: false } },
      scales:  { x: { beginAtZero: true, ticks: { stepSize: 1 } } },
    },
  });
})();
