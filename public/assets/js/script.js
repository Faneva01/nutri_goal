/**
 * GLOBAL SCRIPTS - Nutri Goal
 */

const TOAST_ICONS = {
    success: '<i class="fas fa-check-circle"></i>',
    error:   '<i class="fas fa-exclamation-circle"></i>',
    warning: '<i class="fas fa-exclamation-triangle"></i>',
    info:    '<i class="fas fa-info-circle"></i>',
    gold:    '<i class="fas fa-crown"></i>'
};

/**
 * Affiche une notification de type Toast
 * @param {string} type - success | error | warning | info | gold
 * @param {string} message - Le message à afficher
 */
function showToast(type, message) {
    let toast = document.getElementById('toast');
    
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'toast';
        document.body.appendChild(toast);
    }

    const icon = TOAST_ICONS[type] || TOAST_ICONS.info;
    
    toast.innerHTML = `${icon} <span>${message}</span>`;
    toast.className = `toast toast--${type} show`;

    // Reset timer si déjà affiché
    if (toast._timer) clearTimeout(toast._timer);

    toast._timer = setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

// Support pour les anciens appels (compatibilité transitionnelle)
window.showAlert = function(type, message) {
    const toastType = type === 'danger' ? 'error' : type;
    showToast(toastType, message);
};

/**
 * Transforme les selects natifs en selects personnalisés
 */
function initCustomSelects() {
    const selects = document.querySelectorAll('select:not(.no-custom)');

    selects.forEach(select => {
        if (select.classList.contains('has-custom')) return;

        const options = Array.from(select.options);
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';

        const customSelect = document.createElement('div');
        customSelect.className = 'custom-select';
        
        const trigger = document.createElement('div');
        trigger.className = 'custom-select__trigger';
        
        const selectedText = document.createElement('span');
        selectedText.textContent = select.options[select.selectedIndex]?.textContent || 'Sélectionner';
        
        const arrow = document.createElement('div');
        arrow.className = 'arrow';
        arrow.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>`;

        trigger.appendChild(selectedText);
        trigger.appendChild(arrow);

        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'custom-options';

        options.forEach(option => {
            const customOption = document.createElement('span');
            customOption.className = 'custom-option' + (option.selected ? ' selected' : '');
            customOption.textContent = option.textContent;
            customOption.dataset.value = option.value;

            customOption.addEventListener('click', () => {
                if (customOption.classList.contains('selected')) return;

                const parent = customOption.closest('.custom-select');
                parent.querySelector('.custom-option.selected')?.classList.remove('selected');
                customOption.classList.add('selected');
                
                selectedText.textContent = customOption.textContent;
                select.value = customOption.dataset.value;
                
                // Trigger change event on original select
                select.dispatchEvent(new Event('change'));
                
                parent.classList.remove('open');
            });

            optionsContainer.appendChild(customOption);
        });

        customSelect.appendChild(trigger);
        customSelect.appendChild(optionsContainer);
        wrapper.appendChild(customSelect);

        select.classList.add('has-custom');
        select.parentNode.insertBefore(wrapper, select.nextSibling);

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            // Fermer les autres selects ouverts
            document.querySelectorAll('.custom-select.open').forEach(openSelect => {
                if (openSelect !== customSelect) openSelect.classList.remove('open');
            });
            customSelect.classList.toggle('open');
        });
    });
}

// Fermer les selects si on clique ailleurs
document.addEventListener('click', () => {
    document.querySelectorAll('.custom-select.open').forEach(select => {
        select.classList.remove('open');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    initCustomSelects();
});
