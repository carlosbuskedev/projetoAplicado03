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

    const secondaryMissionLink = Array.from(document.querySelectorAll('a.pixel-menu-item')).find((link) => {
        return link.textContent.includes('Missões secundárias');
    });

    if (secondaryMissionLink) {
        secondaryMissionLink.addEventListener('click', async function (event) {
            event.preventDefault();
            const user = AuthSession.getUser();

            try {
                const response = await AuthSession.apiRequest('/api/sidequests/status', {
                    method: 'POST',
                    body: JSON.stringify({
                        user_id: user.id
                    }),
                });

                const body = await response.json();

                if (!response.ok || !body.success) {
                    throw new Error(body.message || 'Não foi possível verificar suas missões secundárias.');
                }

                const destination = body.redirectTo || (body.hasResponses ? '/weekly-diagnostic' : '/side-quests');
                window.location.href = destination;
            } catch (error) {
                console.warn('Erro ao verificar missões secundárias.', error);
                window.location.href = '/side-quests';
            }
        });
    }

    if (AuthSession.hasRole('admin')) {
        const menu = document.getElementById('menuUsuarios');
        if (menu) {
            menu.style.display = '';
        }
    }
});
