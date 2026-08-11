document.addEventListener('DOMContentLoaded', function () {
    if (!AuthSession.requirePage('sidequests')) {
        return;
    }

    const form = document.getElementById('sideQuestForm');
    const alertEl = document.getElementById('sideQuestAlert');

    if (!form) {
        return;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        //const formData = new URLSearchParams();
        const inputs = form.querySelectorAll('input[type=radio]:checked');

        if (inputs.length === 0) {
            alertEl.innerHTML = '<div class="alert alert-danger">Selecione pelo menos uma resposta para cada pergunta.</div>';
            return;
        }

        const user = AuthSession.getUser();

        let arrResponses = [];
        inputs.forEach(input => {
            arrResponses.push({
                'question_id': input.dataset.questionId,
                'scale_id': input.value
            });
        });

        const payload = {
            responses: arrResponses,
            user_id: user ? user.id : null
        };
        
        try {
            const response = await AuthSession.apiRequest('/api/sidequests', {
                method: 'POST',
                body: JSON.stringify(payload),
            });

            const body = await parseResponse(response);

            window.location.href = '/weekly-diagnostic';
        } catch (error) {
            alertEl.innerHTML = `<div class="alert alert-danger">${error.message || 'Erro ao enviar respostas.'}</div>`;
            return;
        }
    });

    async function parseResponse(response) {
        const body = await response.json();

        if (!response.ok) {
        throw new Error(body.message || 'Erro na requisição.');
        }

        return body;
    }
});
