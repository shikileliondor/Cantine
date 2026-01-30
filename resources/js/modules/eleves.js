const elevesRoot = document.querySelector('[data-module="eleves"]');

if (elevesRoot) {
    const searchInput = elevesRoot.querySelector('input[placeholder="Nom ou prénom"]');

    window.addEventListener('keydown', (event) => {
        if (event.key === '/' && document.activeElement !== searchInput) {
            event.preventDefault();
            searchInput?.focus();
        }
    });
}
