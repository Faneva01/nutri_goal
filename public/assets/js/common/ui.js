const TOAST_ICONS = {
  success: '<i class="fas fa-check-circle"></i>',
  error:   '<i class="fas fa-times-circle"></i>',
  warning: '<i class="fas fa-exclamation-triangle"></i>',
  info:    '<i class="fas fa-info-circle"></i>'
};

function showToast(type, message) {
  let toast = document.getElementById('toast');
  if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toast';
      document.body.appendChild(toast);
  }
  
  const icon  = TOAST_ICONS[type] || TOAST_ICONS.info;
  toast.innerHTML = `${icon} <span>${message}</span>`;
  toast.className = `toast toast--${type} show`;
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 3000);
}
