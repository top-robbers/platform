# Top Robbers - Web Platform

Official Laravel-based web platform for **Top Robbers**.

This repository contains the public website, authentication system, player dashboard, admin panel and internal APIs used by the Top Robbers ecosystem.

## Features

- Public website
- User registration and authentication
- Player dashboard / UCP
- Admin panel / ACP
- Roadmap and news pages
- Internal APIs for launcher and gamemode integration
- PostgreSQL database
- Redis cache / queues / sessions
- Mailpit for local email testing
- React + Inertia frontend
- Docker-based local development environment

## Tech Stack

- Laravel
- React
- Inertia
- TypeScript
- Tailwind CSS
- PostgreSQL
- Redis
- Docker Compose

Laravel’s React starter kit uses React, Inertia, TypeScript, Tailwind, which is the base stack used by this project.

## Requirements

You only need:

- Docker
- Docker Compose v2
- Git

You do **not** need to install PHP, Composer, Node.js, PostgreSQL or Redis locally.

On Windows, Docker Desktop is recommended.

## Local Development Setup

Clone the repository:

```bash
git clone https://github.com/top-robbers/platform.git
cd platform
```

Copy the environment file:

```bash
cp .env.example .env
```

Start the Docker stack:

```bash
docker compose up -d --build
```

Install PHP dependencies:

```bash
docker compose exec app composer install
```

Install frontend dependencies:

```bash
docker compose exec node npm install
```

Generate the Laravel application key:

```bash
docker compose exec app php artisan key:generate
```

Run database migrations:

```bash
docker compose exec app php artisan migrate
```

The application should now be available at:

```txt
https://top-robbers.test
```

Mailpit is available at:

```txt
https://mail.top-robbers.test
```

## Local HTTPS

This project uses a local reverse proxy with HTTPS support.

Add the following entries to your system hosts file:

```txt
127.0.0.1 top-robbers.test
127.0.0.1 vite.top-robbers.test
127.0.0.1 mail.top-robbers.test
```

On Windows, the hosts file is located at:

```txt
C:\Windows\System32\drivers\etc\hosts
```

After starting the stack, you may need to trust the local Caddy certificate.

Copy the local root certificate from the Caddy container:

```bash
docker compose cp caddy:/data/caddy/pki/authorities/local/root.crt ./docker/caddy/certs/caddy-local-root.crt
```

Then import it into your system trusted root certificates.

On Windows PowerShell:

```powershell
Import-Certificate `
  -FilePath .\docker\caddy\certs\caddy-local-root.crt `
  -CertStoreLocation Cert:\CurrentUser\Root
```

## Docker Services

The local development stack contains:

| Service | Description |
| --- | --- |
| `app` | PHP-FPM Laravel application |
| `caddy` | Local HTTPS reverse proxy |
| `node` | Vite development server |
| `postgres` | PostgreSQL database |
| `redis` | Redis cache / queue / session store |
| `mailpit` | Local email testing inbox |

## Common Commands

Start the stack:

```bash
docker compose up -d
```

Stop the stack:

```bash
docker compose down
```

Rebuild containers:

```bash
docker compose up -d --build
```

View logs:

```bash
docker compose logs -f
```

View Laravel logs:

```bash
docker compose logs -f app
```

Run Artisan commands:

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan config:clear
```

Run Composer commands:

```bash
docker compose exec app composer install
docker compose exec app composer update
```

Run NPM commands:

```bash
docker compose exec node npm install
docker compose exec node npm run dev
docker compose exec node npm run build
```

## Installing Composer Packages

Composer packages must be installed inside the `app` container.

Example:

```bash
docker compose exec app composer require vendor/package
```

After installing a package, commit the updated dependency files:

```txt
composer.json
composer.lock
```

Do not commit:

```txt
vendor/
```

## Installing NPM Packages

NPM packages must be installed inside the `node` container.

Example:

```bash
docker compose exec node npm install package-name
```

After installing a package, commit:

```txt
package.json
package-lock.json
```

Do not commit:

```txt
node_modules/
```

## Database

The local PostgreSQL service uses:

```txt
Host: postgres
Port: 5432
Database: top_robbers
Username: top_robbers
Password: secret
```

From the host machine, PostgreSQL is exposed on:

```txt
localhost:5432
```

Laravel should use the Docker service name:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=top_robbers
DB_USERNAME=top_robbers
DB_PASSWORD=secret
```

## Redis

Redis is used for cache, queues and sessions.

Laravel should use:

```env
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Mailpit

Mailpit catches local outgoing emails.

Laravel mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@top-robbers.test"
MAIL_FROM_NAME="${APP_NAME}"
```

Mailpit UI:

```txt
https://mail.top-robbers.test
```

## Volumes

The project uses Docker volumes for heavy dependency folders:

```yaml
vendor:/var/www/html/vendor
node_modules:/var/www/html/node_modules
```

This improves performance on Windows because PHP and Node dependencies are stored inside Docker volumes instead of the Windows bind mount.

If you run:

```bash
docker compose down -v
```

Docker will delete all volumes, including:

- PostgreSQL data
- Redis data
- Composer `vendor`
- Node `node_modules`

After that, you must reinstall dependencies:

```bash
docker compose exec app composer install
docker compose exec node npm install
docker compose exec app php artisan migrate
```

## Testing

Run the test suite:

```bash
docker compose exec app php artisan test
```

Or directly with Pest:

```bash
docker compose exec app ./vendor/bin/pest
```

## Code Style

Format PHP code:

```bash
docker compose exec app ./vendor/bin/pint
```

Build frontend assets:

```bash
docker compose exec node npm run build
```

## Project Structure

```txt
app/                  Laravel application code
bootstrap/            Laravel bootstrap files
config/               Laravel configuration
database/             migrations, seeders and factories
docker/               local Docker configuration
public/               public web root
resources/js/         React / Inertia frontend
resources/css/        styles
routes/               Laravel routes
tests/                automated tests
```

## Architecture Notes

Laravel is the central platform for:

- Web authentication
- Public website
- UCP
- ACP
- Launcher API
- Internal server API

The platform may read gamemode data for display purposes, but sensitive gamemode data changes should be performed through audited actions or gamemode commands, not through direct unrestricted SQL updates.

## License

See [LICENSE](LICENSE.md).
