const initSidebar = () => {
    const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    const closeSidebar = () => {
        if (!sidebar || !overlay) {
            return;
        }

        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    };

    const openSidebar = () => {
        if (!sidebar || !overlay) {
            return;
        }

        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    };

    toggleButtons.forEach((button) => {
        if (button.dataset.sidebarBound === 'true') {
            return;
        }

        button.dataset.sidebarBound = 'true';
        button.addEventListener('click', () => {
            if (!sidebar) {
                return;
            }

            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    });

    if (overlay && overlay.dataset.sidebarBound !== 'true') {
        overlay.dataset.sidebarBound = 'true';
        overlay.addEventListener('click', closeSidebar);
    }

    if (!window.__cantineSidebarResizeBound) {
        window.__cantineSidebarResizeBound = true;
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                if (overlay) {
                    overlay.classList.add('hidden');
                }

                if (sidebar) {
                    sidebar.classList.remove('-translate-x-full');
                }
            }
        });
    }
};

const applyTheme = (theme) => {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    localStorage.setItem('cantine-theme', theme);
};

const initThemeToggle = () => {
    const toggleButton = document.querySelector('[data-theme-toggle]');
    if (!toggleButton) {
        return;
    }

    const syncState = () => {
        const isDark = document.documentElement.classList.contains('dark');
        toggleButton.setAttribute('aria-pressed', isDark ? 'true' : 'false');
    };

    if (toggleButton.dataset.themeBound === 'true') {
        syncState();
        return;
    }

    toggleButton.dataset.themeBound = 'true';
    toggleButton.addEventListener('click', () => {
        const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
        applyTheme(nextTheme);
        syncState();
    });

    syncState();
};

const initUi = () => {
    initSidebar();
    initThemeToggle();
};

initUi();

document.addEventListener('livewire:navigated', initUi);
