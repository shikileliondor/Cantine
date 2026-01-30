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

if (overlay) {
    overlay.addEventListener('click', closeSidebar);
}

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
