document.addEventListener('DOMContentLoaded', () => {
    if (AuthSession.isAuthenticated()) {
        AuthSession.redirectAfterLogin();
        return;
    }

    document.getElementById('loginForm').addEventListener('submit', handleLoginPage);
});

async function handleLoginPage(event) {
    event.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const btn = document.getElementById('btnEntrar');
    const alertEl = document.getElementById('loginAlert');

    btn.disabled = true;
    alertEl.classList.add('d-none');

    try {
        await AuthSession.login(email, password);
        AuthSession.redirectAfterLogin();
    } catch (error) {
        alertEl.textContent = error.message;
        alertEl.className = 'alert alert-danger pixel-alert';
        alertEl.classList.remove('d-none');
    } finally {
        btn.disabled = false;
    }
}
