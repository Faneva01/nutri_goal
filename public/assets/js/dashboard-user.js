document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.kpi-card, .panel');

  cards.forEach((card, idx) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(8px)';
    setTimeout(() => {
      card.style.transition = 'all 220ms ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, 40 * idx);
  });

  renderWeightChart();
  renderMacroChart();
  renderCalorieChart();
});

function renderWeightChart() {
  const dataEl = document.getElementById('weight-series-data');
  const canvas = document.getElementById('weightChart');

  if (!dataEl || !canvas) {
    return;
  }

  let points = [];
  try {
    points = JSON.parse(dataEl.textContent || '[]');
  } catch (e) {
    points = [];
  }

  if (!Array.isArray(points) || points.length === 0) {
    return;
  }

  const ctx = canvas.getContext('2d');
  const dpr = window.devicePixelRatio || 1;
  const width = canvas.clientWidth || 760;
  const height = canvas.height || 280;
  canvas.width = Math.floor(width * dpr);
  canvas.height = Math.floor(height * dpr);
  ctx.scale(dpr, dpr);
  ctx.clearRect(0, 0, width, height);

  const padding = { top: 16, right: 16, bottom: 34, left: 42 };
  const chartWidth = width - padding.left - padding.right;
  const chartHeight = height - padding.top - padding.bottom;

  const weights = points.map(p => Number(p.weight) || 0);
  const minW = Math.min(...weights);
  const maxW = Math.max(...weights);
  const spread = Math.max(0.8, maxW - minW);
  const from = minW - spread * 0.2;
  const to = maxW + spread * 0.2;

  // Grid
  ctx.strokeStyle = '#eee';
  ctx.lineWidth = 1;
  for (let i = 0; i <= 4; i++) {
    const y = padding.top + (chartHeight / 4) * i;
    ctx.beginPath();
    ctx.moveTo(padding.left, y);
    ctx.lineTo(width - padding.right, y);
    ctx.stroke();
  }

  const toX = idx => padding.left + (chartWidth * idx) / Math.max(1, points.length - 1);
  const toY = weight => padding.top + ((to - weight) / (to - from)) * chartHeight;

  // Line
  ctx.strokeStyle = '#6A7C4F';
  ctx.lineWidth = 3;
  ctx.beginPath();
  points.forEach((p, idx) => {
    const x = toX(idx);
    const y = toY(Number(p.weight) || 0);
    if (idx === 0) ctx.moveTo(x, y);
    else ctx.lineTo(x, y);
  });
  ctx.stroke();

  // Points + labels
  ctx.fillStyle = '#E17864';
  ctx.font = '12px sans-serif';
  points.forEach((p, idx) => {
    const x = toX(idx);
    const y = toY(Number(p.weight) || 0);
    ctx.beginPath();
    ctx.arc(x, y, 3.5, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillText(String(p.day || ''), x - 12, height - 10);
  });

  // Y labels (min/max)
  ctx.fillStyle = '#444';
  ctx.fillText(`${from.toFixed(1)} kg`, 4, height - padding.bottom);
  ctx.fillText(`${to.toFixed(1)} kg`, 4, padding.top + 4);
}

function renderMacroChart() {
  const dataEl = document.getElementById('macro-data');
  const canvas = document.getElementById('macroChart');
  if (!dataEl || !canvas) return;

  let macro = {};
  try {
    macro = JSON.parse(dataEl.textContent || '{}');
  } catch (e) {
    macro = {};
  }

  const values = [
    Number(macro.proteines) || 35,
    Number(macro.glucides) || 40,
    Number(macro.lipides) || 25,
  ];
  const colors = ['#E17864', '#DBEB94', '#6A7C4F'];
  const total = values.reduce((a, b) => a + b, 0) || 1;

  const ctx = canvas.getContext('2d');
  const w = canvas.width;
  const h = canvas.height;
  const cx = w / 2;
  const cy = h / 2;
  const radius = 92;
  const inner = 52;

  ctx.clearRect(0, 0, w, h);
  let start = -Math.PI / 2;
  values.forEach((val, idx) => {
    const slice = (val / total) * Math.PI * 2;
    ctx.beginPath();
    ctx.moveTo(cx, cy);
    ctx.arc(cx, cy, radius, start, start + slice);
    ctx.closePath();
    ctx.fillStyle = colors[idx];
    ctx.fill();
    start += slice;
  });

  ctx.beginPath();
  ctx.arc(cx, cy, inner, 0, Math.PI * 2);
  ctx.fillStyle = '#fff';
  ctx.fill();
}

function renderCalorieChart() {
  const dataEl = document.getElementById('calorie-data');
  const canvas = document.getElementById('calorieChart');
  if (!dataEl || !canvas) return;

  let points = [];
  try {
    points = JSON.parse(dataEl.textContent || '[]');
  } catch (e) {
    points = [];
  }
  if (!Array.isArray(points) || points.length === 0) return;

  const ctx = canvas.getContext('2d');
  const dpr = window.devicePixelRatio || 1;
  const width = canvas.clientWidth || 760;
  const height = canvas.height || 240;
  canvas.width = Math.floor(width * dpr);
  canvas.height = Math.floor(height * dpr);
  ctx.scale(dpr, dpr);
  ctx.clearRect(0, 0, width, height);

  const pad = { top: 16, right: 16, bottom: 32, left: 40 };
  const chartW = width - pad.left - pad.right;
  const chartH = height - pad.top - pad.bottom;
  const values = points.map(p => Number(p.value) || 0);
  const max = Math.max(...values, 2200);

  ctx.strokeStyle = '#efe8e3';
  for (let i = 0; i <= 4; i++) {
    const y = pad.top + (chartH / 4) * i;
    ctx.beginPath();
    ctx.moveTo(pad.left, y);
    ctx.lineTo(width - pad.right, y);
    ctx.stroke();
  }

  const barW = (chartW / points.length) * 0.78;
  const gap = (chartW / points.length) * 0.22;
  points.forEach((p, i) => {
    const val = Number(p.value) || 0;
    const hBar = (val / max) * chartH;
    const x = pad.left + i * (barW + gap);
    const y = pad.top + chartH - hBar;
    ctx.fillStyle = '#E17864';
    ctx.fillRect(x, y, barW, hBar);
    ctx.fillStyle = '#807872';
    ctx.font = '12px sans-serif';
    ctx.fillText(String(p.month || ''), x + barW / 2 - 12, height - 8);
  });
}
