let userModal;
let userForm;
let userModalEl;

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

    userModalEl = document.getElementById('userModal');
    userModal = new bootstrap.Modal(userModalEl);
    userForm = document.getElementById('userForm');

    document.getElementById('btnNovoUsuario').addEventListener('click', () => openUserModal());
    userForm.addEventListener('submit', saveUser);

    ['userName', 'userEmail', 'userPassword', 'userRole'].forEach((id) => {
        userModalEl.querySelector(`#${id}`)?.addEventListener('input', () => clearFieldError(id));
        userModalEl.querySelector(`#${id}`)?.addEventListener('change', () => clearFieldError(id));
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
    clearPageAlert();

    document.getElementById('userId').value = id ?? '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userRole').value = 'user';

    const hint = document.getElementById('passwordHint');
    const passwordInput = document.getElementById('userPassword');

    if (id) {
        document.getElementById('userModalTitle').textContent = 'Editar usuário';
        hint.textContent = 'Deixe em branco para manter a senha atual.';
        loadUserForEdit(id);
    } else {
        document.getElementById('userModalTitle').textContent = 'Novo usuário';
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
        displayModalErrors(error.message);
    }
}

function getFormValue(id) {
    return userModalEl.querySelector(`#${id}`)?.value ?? '';
}

function validateUserForm() {
    clearFormErrors();
    clearFormAlert();

    const id = getFormValue('userId');
    const isEdit = Boolean(id);
    const name = getFormValue('userName').trim();
    const email = getFormValue('userEmail').trim();
    const password = getFormValue('userPassword');
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
        clearPageAlert();
        userModal.show();
        focusFirstInvalidField();
        return;
    }

    const id = getFormValue('userId');
    const payload = {
        name: getFormValue('userName').trim(),
        email: getFormValue('userEmail').trim(),
        password: getFormValue('userPassword'),
        role: getFormValue('userRole'),
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
        clearPageAlert();
        userModal.show();
        displayModalErrors(error.message);
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
    const input = userModalEl.querySelector(`#${inputId}`);
    const error = userModalEl.querySelector(`#${errorId}`);

    if (!input || !error) {
        return false;
    }

    input.classList.add('is-invalid');
    input.setAttribute('aria-invalid', 'true');
    error.textContent = message;
    error.classList.remove('d-none');
    error.classList.add('show');
    return true;
}

function clearFieldError(inputId) {
    const input = userModalEl.querySelector(`#${inputId}`);
    const error = userModalEl.querySelector(`#${inputId}Error`);

    if (!input || !error) {
        return;
    }

    input.classList.remove('is-invalid');
    input.removeAttribute('aria-invalid');
    error.textContent = '';
    error.classList.add('d-none');
    error.classList.remove('show');
}

function clearFormErrors() {
    ['userName', 'userEmail', 'userPassword', 'userRole'].forEach((id) => clearFieldError(id));
}

function displayModalErrors(message) {
    clearPageAlert();
    clearFormAlert();

    const mapped = applyServerValidationErrors(message);

    if (!mapped) {
        showFormAlert(message, 'danger');
    }

    focusFirstInvalidField();
}

function showFormAlert(message, type) {
    const el = userModalEl.querySelector('#formAlert');
    if (!el) {
        return;
    }

    el.textContent = message;
    el.className = `alert alert-${type} mb-3`;
    el.classList.remove('d-none');
    scrollWithinModal(el);
}

function clearFormAlert() {
    const el = userModalEl?.querySelector('#formAlert');
    if (!el) {
        return;
    }

    el.classList.add('d-none');
    el.textContent = '';
}

function clearPageAlert() {
    const el = document.getElementById('alertBox');
    if (!el) {
        return;
    }

    el.classList.add('d-none');
    el.textContent = '';
}

function focusFirstInvalidField() {
    const firstInvalid = userModalEl.querySelector('.pixel-input.is-invalid');
    if (!firstInvalid) {
        return;
    }

    firstInvalid.focus();
    scrollWithinModal(firstInvalid);
}

function scrollWithinModal(element) {
    const modalBody = userModalEl?.querySelector('.modal-body');
    if (!modalBody || !element) {
        return;
    }

    const bodyTop = modalBody.getBoundingClientRect().top;
    const elementTop = element.getBoundingClientRect().top;
    modalBody.scrollTop += elementTop - bodyTop - 12;
}

function applyServerValidationErrors(message) {
    const normalized = (message ?? '').toLowerCase();
    let mapped = false;

    if (normalized.includes('nome') && normalized.includes('e-mail')) {
        mapped = setFieldError('userName', 'userNameError', 'Nome é obrigatório.') || mapped;
        mapped = setFieldError('userEmail', 'userEmailError', 'E-mail é obrigatório.') || mapped;
        return mapped;
    }

    if (normalized.includes('nome é obrigatório') || normalized === 'nome é obrigatório.') {
        return setFieldError('userName', 'userNameError', 'Nome é obrigatório.');
    }

    if (normalized.includes('e-mail é obrigatório') || normalized.includes('email é obrigatório')) {
        return setFieldError('userEmail', 'userEmailError', 'E-mail é obrigatório.');
    }

    if (normalized.includes('e-mail inválido') || normalized.includes('email inválido')) {
        return setFieldError('userEmail', 'userEmailError', 'E-mail inválido.');
    }

    if (normalized.includes('e-mail já') || normalized.includes('email já')) {
        return setFieldError('userEmail', 'userEmailError', message);
    }

    if (normalized.includes('senha é obrigatória')) {
        return setFieldError('userPassword', 'userPasswordError', 'Senha é obrigatória no cadastro.');
    }

    if (normalized.includes('senha deve ter') || normalized.includes('mínimo 6')) {
        return setFieldError('userPassword', 'userPasswordError', 'A senha deve ter no mínimo 6 caracteres.');
    }

    if (normalized.includes('perfil inválido')) {
        return setFieldError('userRole', 'userRoleError', 'Perfil inválido. Use admin ou user.');
    }

    return mapped;
}

function extractApiMessage(body) {
    if (body?.message) {
        return body.message;
    }

    const messages = body?.messages;

    if (!messages) {
        return 'Erro na requisição.';
    }

    if (typeof messages === 'string') {
        return messages;
    }

    if (messages.error) {
        return messages.error;
    }

    const first = Object.values(messages)[0];

    if (typeof first === 'string') {
        return first;
    }

    if (Array.isArray(first) && first.length > 0) {
        return first[0];
    }

    return 'Erro na requisição.';
}

async function parseResponse(response) {
    const body = await response.json();

    if (!response.ok) {
        throw new Error(extractApiMessage(body));
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
