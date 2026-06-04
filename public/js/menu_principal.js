document.addEventListener('DOMContentLoaded', function () {
    if (!AuthSession.requireAuth(['admin', 'user'])) {
        return;
    }

    const user = AuthSession.getUser();
    const btn = document.querySelector('.pixel-btn-login');

    if (btn) {
        btn.textContent = user?.email ?? 'Conta';
        btn.onclick = () => {
            if (confirm('Deseja sair?')) {
                AuthSession.clearSession();
                window.location.href = '/login';
            }
        };
    }

    if (AuthSession.hasRole('admin')) {
        const menu = document.getElementById('menuUsuarios');
        if (menu) {
            menu.style.display = '';
        }
    }
});
