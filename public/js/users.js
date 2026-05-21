let userModal;
let userForm;

function setupAccountButton() {
    const btn = document.getElementById('btnConta');
    if (!btn) {
        return;
    }

    const user = AuthSession.getUser();
    btn.textContent = user?.email ?? 'Conta';
    btn.onclick = () => {
        if (confirm('Deseja sair?')) {
            AuthSession.clearSession();
            window.location.href = '/login';
        }
    };
}

document.addEventListener('DOMContentLoaded', () => {
    if (!AuthSession.requirePage('users')) {
        return;
    }

    setupAccountButton();

    userModal = new bootstrap.Modal(document.getElementById('userModal'));
    userForm = document.getElementById('userForm');

    document.getElementById('btnNovoUsuario').addEventListener('click', () => openUserModal());
    userForm.addEventListener('submit', saveUser);

    loadUsers();
});

async function loadUsers() {
    const tbody = document.getElementById('usersTableBody');

    try {
        const response = await AuthSession.apiRequest('/api/users');
        const body = await parseResponse(response);

        if (!body.data?.users?.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Nenhum usuário cadastrado.</td></tr>';
            return;
        }

        tbody.innerHTML = body.data.users.map((user) => `
            <tr>
                <td>${user.id}</td>
                <td>${escapeHtml(user.name)}</td>
                <td>${escapeHtml(user.email)}</td>
                <td><span class="badge ${user.role === 'admin' ? 'bg-danger' : 'bg-secondary'}">${user.role}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-warning btn-action" onclick="openUserModal(${user.id})">Editar</button>
                    <button class="btn btn-sm btn-outline-danger btn-action" onclick="deleteUser(${user.id})">Excluir</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${escapeHtml(error.message)}</td></tr>`;
    }
}

function openUserModal(id = null) {
    document.getElementById('userId').value = id ?? '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userRole').value = 'user';

    const hint = document.getElementById('passwordHint');
    const passwordInput = document.getElementById('userPassword');

    if (id) {
        document.getElementById('userModalTitle').textContent = 'Editar usuário';
        passwordInput.removeAttribute('required');
        hint.textContent = 'Deixe em branco para manter a senha atual.';
        loadUserForEdit(id);
    } else {
        document.getElementById('userModalTitle').textContent = 'Novo usuário';
        passwordInput.setAttribute('required', 'required');
        hint.textContent = 'Obrigatória no cadastro.';
    }

    userModal.show();
}

async function loadUserForEdit(id) {
    try {
        const response = await AuthSession.apiRequest(`/api/users/${id}`);
        const body = await parseResponse(response);
        const user = body.data.user;

        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userRole').value = user.role;
    } catch (error) {
        showAlert(error.message, 'danger');
        userModal.hide();
    }
}

async function saveUser(event) {
    event.preventDefault();

    const id = document.getElementById('userId').value;
    const payload = {
        name: document.getElementById('userName').value.trim(),
        email: document.getElementById('userEmail').value.trim(),
        password: document.getElementById('userPassword').value,
        role: document.getElementById('userRole').value,
    };

    const isEdit = Boolean(id);
    const url = isEdit ? `/api/users/${id}` : '/api/users';
    const method = isEdit ? 'PUT' : 'POST';

    try {
        const response = await AuthSession.apiRequest(url, {
            method,
            body: JSON.stringify(payload),
        });

        const body = await parseResponse(response);
        showAlert(body.message, 'success');
        userModal.hide();
        loadUsers();
    } catch (error) {
        showAlert(error.message, 'danger');
    }
}

async function deleteUser(id) {
    if (!confirm('Deseja excluir este usuário?')) {
        return;
    }

    try {
        const response = await AuthSession.apiRequest(`/api/users/${id}`, { method: 'DELETE' });
        const body = await parseResponse(response);
        showAlert(body.message, 'success');
        loadUsers();
    } catch (error) {
        showAlert(error.message, 'danger');
    }
}

async function parseResponse(response) {
    const body = await response.json();

    if (!response.ok) {
        throw new Error(body.message ?? 'Erro na requisição.');
    }

    return body;
}

function showAlert(message, type) {
    const el = document.getElementById('alertBox');
    el.textContent = message;
    el.className = `alert alert-${type}`;
    el.classList.remove('d-none');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
