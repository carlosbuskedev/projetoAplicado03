# BRIO

Aplicação web de produtividade gamificada desenvolvida como Projeto Aplicado. O BRIO transforma tarefas do dia a dia em **missões**, com timer estilo Pomodoro, progressão de personagem e painel administrativo.

## Sobre o projeto

O BRIO ajuda o usuário a organizar atividades, iniciar jornadas focadas e acompanhar evolução com elementos de RPG (experiência, nível e progressão). Administradores gerenciam usuários e acompanham o sistema pelo painel admin.

## Funcionalidades

- **Autenticação JWT** com perfis `admin` e `user`
- **Missões (Quests)** — cadastro com título, descrição, tempo estimado, dificuldade, prioridade e prazo
- **Jornada** — execução de missões com timer, pausas e conclusão
- **Progressão (Leveling)** — ficha de personagem com XP e evolução
- **Usuários (admin)** — CRUD completo com validação no frontend e backend
- **API REST** protegida por filtros JWT e controle de perfil

## Tecnologias

| Camada | Tecnologia |
|--------|------------|
| Backend | PHP 8.2, CodeIgniter 4 |
| Frontend | HTML, CSS, JavaScript (Bootstrap 5) |
| Banco | MySQL / MariaDB |
| Auth | JWT (Bearer token) |
| Deploy | Docker, Apache, Coolify |

## Estrutura do projeto

```
app/
├── Controllers/
│   ├── Backend/     # API (Auth, Users, Quests)
│   └── Frontend/    # Páginas (Login, Home, Journey, Quests, Users...)
├── Models/Backend/
├── Services/        # Regras de negócio (Auth, User, Quest)
├── Filters/         # JwtAuthFilter, RoleFilter
├── Views/
└── Database/
    ├── Migrations/
    └── Seeds/

public/
├── css/
├── js/
└── index.php        # Ponto de entrada (DocumentRoot)
```

## Requisitos

- PHP 8.2+
- Composer
- MySQL / MariaDB
- Extensões PHP: `intl`, `mbstring`, `mysqli` (ou `pdo_mysql`)

## Instalação local (XAMPP)

1. Clone o repositório em `htdocs`:

```bash
git clone https://github.com/carlosbuskedev/projetoAplicado03.git
cd projetoAplicado03
```

2. Instale as dependências:

```bash
composer install
```

3. Copie o arquivo de ambiente e configure:

```bash
copy .env.example .env
```

Edite o `.env` com host, banco, usuário, senha e `JWT_SECRET`.

4. Crie o banco de dados e execute migrações + seed:

```bash
php spark migrate
php spark db:seed UserSeeder
```

5. Aponte o Apache para a pasta `public/` ou acesse:

```
http://localhost/projetoAplicado03/public
```

## Usuários de teste (seed)

| Perfil | E-mail | Senha |
|--------|--------|-------|
| Admin | admin@exemplo.com | admin123 |
| Usuário | user@exemplo.com | user123 |

## Rotas principais

| Rota | Descrição | Acesso |
|------|-----------|--------|
| `/` ou `/login` | Login | Público |
| `/home` | Menu do usuário | user |
| `/home-admin` | Painel admin | admin |
| `/quests` | Missões | autenticado |
| `/journey` | Jornada | autenticado |
| `/leveling` | Progressão | autenticado |
| `/users` | Gerenciar usuários | admin |

## API (resumo)

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/auth/login` | Login (retorna JWT) |
| GET | `/api/auth/me` | Perfil do usuário logado |
| GET/POST/PUT/DELETE | `/api/users` | CRUD de usuários (admin) |
| GET/POST/PATCH | `/api/quests` | Missões (autenticado) |

Todas as rotas protegidas exigem header:

```
Authorization: Bearer <token>
```

## Deploy com Docker (Coolify)

O projeto inclui `Dockerfile` e `docker/entrypoint.sh`. Configure no Coolify:

- `database.default.hostname`
- `database.default.database`
- `database.default.username`
- `database.default.password`
- `database.default.DBDriver` = `MySQLi`
- `database.default.port`
- `JWT_SECRET`
- `app.baseURL`

> Não copie um `.env` fixo para dentro do container em produção — use as variáveis de ambiente do Coolify.

Build e execução local:

```bash
docker build -t brio .
docker run -p 8080:80 --env-file .env brio
```

## Autores

- **Camila Sixel Cordeiro** — [@csixel](https://github.com/csixel)
- **Carlos Guilherme da Silva Buske** — [@Carlosguilherme95](https://github.com/Carlosguilherme95)

Projeto desenvolvido na **Universidade Federal Fluminense (UFF)**.

## Licença

Projeto acadêmico. O framework CodeIgniter 4 é distribuído sob licença MIT.
