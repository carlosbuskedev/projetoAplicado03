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

    ['userName', 'userEmail', 'userPassword'].forEach((id) => {
        document.getElementById(id).addEventListener('input', () => clearFieldError(id));
    });

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
    clearFormErrors();
    clearFormAlert();

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
        hint.textContent = 'Obrigatória no cadastro (mínimo 6 caracteres).';
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
        showFormAlert(error.message, 'danger');
        userModal.hide();
    }
}

function validateUserForm() {
    clearFormErrors();
    clearFormAlert();

    const id = document.getElementById('userId').value;
    const isEdit = Boolean(id);
    const name = document.getElementById('userName').value.trim();
    const email = document.getElementById('userEmail').value.trim();
    const password = document.getElementById('userPassword').value;
    let valid = true;

    if (name === '') {
        setFieldError('userName', 'userNameError', 'Nome é obrigatório.');
        valid = false;
    }

    if (email === '') {
        setFieldError('userEmail', 'userEmailError', 'E-mail é obrigatório.');
        valid = false;
    } else if (!isValidEmail(email)) {
        setFieldError('userEmail', 'userEmailError', 'E-mail inválido.');
        valid = false;
    }

    if (!isEdit && password === '') {
        setFieldError('userPassword', 'userPasswordError', 'Senha é obrigatória no cadastro.');
        valid = false;
    } else if (password !== '' && password.length < 6) {
        setFieldError('userPassword', 'userPasswordError', 'A senha deve ter no mínimo 6 caracteres.');
        valid = false;
    }

    return valid;
}

async function saveUser(event) {
    event.preventDefault();

    if (!validateUserForm()) {
        showFormAlert('Corrija os campos destacados antes de salvar.', 'warning');
        return;
    }

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
        userModal.hide();
        showAlert(body.message, 'success');
        loadUsers();
    } catch (error) {
        showFormAlert(error.message, 'danger');
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

function setFieldError(inputId, errorId, message) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(errorId);

    input.classList.add('is-invalid');
    error.textContent = message;
    error.classList.remove('d-none');
}

function clearFieldError(inputId) {
    const input = document.getElementById(inputId);
    const error = document.getElementById(`${inputId}Error`);

    if (!input || !error) {
        return;
    }

    input.classList.remove('is-invalid');
    error.classList.add('d-none');
}

function clearFormErrors() {
    ['userName', 'userEmail', 'userPassword'].forEach((id) => clearFieldError(id));
}

function showFormAlert(message, type) {
    const el = document.getElementById('formAlert');
    el.textContent = message;
    el.className = `alert alert-${type} mb-3`;
    el.classList.remove('d-none');
}

function clearFormAlert() {
    const el = document.getElementById('formAlert');
    el.classList.add('d-none');
    el.textContent = '';
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

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
