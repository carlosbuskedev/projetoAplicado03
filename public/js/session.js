/**
 * Sessão JWT no navegador (localStorage) fase de testes.
 * depois vou mudar para utilizar cookies
 * teste
 */
const AuthSession = (() => {
    const TOKEN_KEY = 'auth_token';
    const USER_KEY  = 'auth_user';
    const PERMS_KEY = 'auth_permissions';

    const PAGE_ROLES = {
        journey: ['admin', 'user'],
        quests: ['admin', 'user'],
        leveling: ['admin', 'user'],
        users: ['admin'],
        home_admin: ['admin'],
        sidequests: ['admin', 'user'],
        weekly_diagnostic: ['admin', 'user'],
    };

    const ROUTES = {
        login: '/login',
        home: '/home',
        home_admin: '/home-admin',
    };

    function getApiBase() {
        return window.location.origin;
    }

    function getToken() {
        return localStorage.getItem(TOKEN_KEY);
    }

    function setSession({ token, user, permissions }) {
        localStorage.setItem(TOKEN_KEY, token);
        localStorage.setItem(USER_KEY, JSON.stringify(user));
        localStorage.setItem(PERMS_KEY, JSON.stringify(permissions ?? []));
    }

    function getUser() {
        const raw = localStorage.getItem(USER_KEY);
        return raw ? JSON.parse(raw) : null;
    }

    function getRole() {
        return getUser()?.role ?? null;
    }

    function clearSession() {
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
        localStorage.removeItem(PERMS_KEY);
    }

    function isAuthenticated() {
        return Boolean(getToken());
    }

    function hasRole(...roles) {
        const role = getRole();
        return role !== null && roles.includes(role);
    }

    function canAccessPage(pageKey) {
        const allowed = PAGE_ROLES[pageKey];
        return allowed ? hasRole(...allowed) : false;
    }

    function redirectToLogin() {
        const path = window.location.pathname.replace(/\/$/, '') || '/';
        const loginPaths = ['/', '/login'];

        if (!loginPaths.includes(path)) {
            window.location.href = ROUTES.login;
        }
    }

    function redirectAfterLogin() {
        if (hasRole('admin')) {
            window.location.href = ROUTES.home_admin;
        } else {
            window.location.href = ROUTES.home;
        }
    }

    function requireAuth(roles = null) {
        if (!isAuthenticated()) {
            redirectToLogin();
            return false;
        }

        if (roles && !hasRole(...roles)) {
            alert('Você não tem permissão para acessar esta área.');
            redirectAfterLogin();
            return false;
        }

        return true;
    }

    function requirePage(pageKey) {
        if (!requireAuth()) {
            return false;
        }

        if (!canAccessPage(pageKey)) {
            alert('Acesso negado.');
            redirectAfterLogin();
            return false;
        }

        return true;
    }

    function authHeaders(extra = {}) {
        const headers = { 'Content-Type': 'application/json', ...extra };
        const token = getToken();

        if (token) {
            headers.Authorization = `Bearer ${token}`;
        }

        return headers;
    }

    async function apiFetch(path, options = {}) {
        const url = `${getApiBase()}/api/auth/${path.replace(/^\//, '')}`;
        return request(url, options);
    }

    async function apiRequest(path, options = {}) {
        const url = `${getApiBase()}${path.startsWith('/') ? path : `/${path}`}`;
        return request(url, options);
    }

    async function request(url, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: authHeaders(options.headers ?? {}),
        });

        if (response.status === 401) {
            clearSession();
            redirectToLogin();
            throw new Error('Sessão expirada. Faça login novamente.');
        }

        return response;
    }

    async function login(email, password) {
        const response = await fetch(`${getApiBase()}/api/auth/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });

        const body = await response.json();

        if (!response.ok) {
            throw new Error(body.message ?? 'Falha no login.');
        }

        setSession({
            token: body.data.token,
            user: body.data.user,
            permissions: body.data.permissions,
        });

        return body;
    }

    return {
        getApiBase,
        getToken,
        setSession,
        getUser,
        getRole,
        clearSession,
        isAuthenticated,
        hasRole,
        canAccessPage,
        requireAuth,
        requirePage,
        authHeaders,
        apiFetch,
        apiRequest,
        login,
        redirectToLogin,
        redirectAfterLogin,
        ROUTES,
        PAGE_ROLES,
    };
})();
