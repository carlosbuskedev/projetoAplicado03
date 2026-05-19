// Inicialização quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    console.log('Menu BRIO carregado!');
});

// Abrir o modal de Login
function openLoginModal() {
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}

// Fechar o modal
function closeLoginModal() {
    const modalElement = document.getElementById('loginModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
}

// Função de login
function handleLogin(event) {
    event.preventDefault(); // evita reload da página

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Exemplo simples (substitua por validação real / API)
    if (username === "admin" && password === "1234") {
        alert("Login realizado com sucesso!");

        closeLoginModal();

        // limpar campos
        document.getElementById('username').value = "";
        document.getElementById('password').value = "";
    } else {
        alert("Usuário ou senha inválidos!");
    }
}
