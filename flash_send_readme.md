# Flash Send - Gestão de Encomendas para Condomínios 🚀

Aplicação web construída em **Laravel 11** para gestão de condomínios, focada no recebimento de encomendas. Quando a portaria registra a chegada de uma encomenda, o morador recebe **notificação via WhatsApp** (em desenvolvimento).

O projeto é totalmente **dockerizado**, permitindo rodar a aplicação sem instalar PHP, Composer, MySQL ou Redis localmente.

---

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel)
![Docker](https://img.shields.io/badge/Docker-Compose-blue?style=for-the-badge&logo=docker)
![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?style=for-the-badge&logo=mysql)

---

## Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## Estrutura do Projeto

```
flash_send/
├─ docker/
│  ├─ php/
│  ├─ nginx/
├─ src/                # Código Laravel
├─ docker-compose.yml
```
> O diretório `src/` é sincronizado com os containers para que qualquer alteração feita localmente reflita no container e vice-versa.

---

## Como Rodar o Projeto

1️⃣ Clone o repositório:

```bash
git clone https://github.com/seu-usuario/flash_send.git
cd flash_send
```

2️⃣ Suba os containers:

```bash
docker compose up -d --build
```

Serviços criados:
- **app** → PHP + Laravel
- **web** → Nginx
- **db** → MySQL
- **redis** → Redis

3️⃣ Instale dependências do Laravel (caso ainda não existam na pasta `src`):

```bash
docker compose exec app bash
composer install
php artisan key:generate
exit
```

4️⃣ Configure o banco de dados (arquivo `.env` dentro de `src/`):

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=flashsend
DB_USERNAME=flashsend
DB_PASSWORD=flashsend
```

5️⃣ Execute migrations:

```bash
docker compose exec app php artisan migrate
```

6️⃣ Acesse a aplicação no navegador:

```
http://localhost:8080
```

---

## Comandos Úteis

- Entrar no container PHP:
```bash
docker compose exec app bash
```
- Ver logs de todos os serviços:
```bash
docker compose logs -f
```
- Parar os containers:
```bash
docker compose down
```
- Limpar containers parados e volumes (CUIDADO: remove dados do MySQL):
```bash
docker compose down -v
```

---

## Licença

Este projeto é open source e pode ser usado e modificado livremente.

