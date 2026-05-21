document.addEventListener('DOMContentLoaded', () => {
    if (!AuthSession.requireAuth(['admin'])) {
        return;
    }

    const user = AuthSession.getUser();
    document.getElementById('adminEmail').textContent = user?.email ?? '';

    document.getElementById('btnLogout').addEventListener('click', () => {
        if (confirm('Deseja sair do painel?')) {
            AuthSession.clearSession();
            window.location.href = '/login';
        }
    });
});
