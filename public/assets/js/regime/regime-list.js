document.addEventListener('DOMContentLoaded', () => {

    const filterType = document.getElementById('filterType');
    const filterIntensite = document.getElementById('filterIntensite');
    const applyFilters = document.getElementById('applyFilters');

    applyFilters.addEventListener('click', () => {
        const type = filterType.value;
        const intensite = filterIntensite.value;

        const url = new URL(window.location);
        
        if (type) {
            url.searchParams.set('type', type);
        } else {
            url.searchParams.delete('type');
        }

        if (intensite) {
            url.searchParams.set('intensite', intensite);
        } else {
            url.searchParams.delete('intensite');
        }

        window.location.href = url.toString();
    });

    // Restaurer les filtres depuis l'URL
    const params = new URLSearchParams(window.location.search);
    if (params.get('type')) {
        filterType.value = params.get('type');
    }
    if (params.get('intensite')) {
        filterIntensite.value = params.get('intensite');
    }

});