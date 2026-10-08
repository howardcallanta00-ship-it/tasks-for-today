# Tasks for Today

Tasks for Today is a production-oriented Technical Assessment Project developed by **Howard Mac Callanta**. It presents today’s active work publicly and gives authenticated users a focused workflow for creating, editing, and archiving tasks.

## Features

- Public today, complete task list, profile, and about pages
- Session login with reusable authorization and safe return redirects
- Create, edit, and soft-archive workflows
- Relative-date seeds that always include work for today
- Validation, CSRF protection, escaped output, and friendly errors
- Responsive, keyboard-friendly custom interface
- MySQL/MariaDB migrations, health endpoint, tests, and Docker deployment

## Requirements

PHP 8.2+, Composer 2, MySQL 8+ or MariaDB 10.6+, and the `intl`, `mbstring`, `mysqli`, `json`, and `curl` PHP extensions. Docker 24+ is optional.

## Local setup

```bash
composer install
cp .env.example .env
php spark migrate --all
php spark db:seed AppSeeder
php spark serve
```

Set `CI_ENVIRONMENT=development`, `app.baseURL`, and `database.default.*` in `.env` first. Open `http://localhost:8080`.

Demo credentials:

- Username: `demo`
- Password: `DemoPass123!`

The credentials are documentation-only and never appear in the public interface. Replace the demo password before storing real data.

## Environment

| Variable | Purpose |
|---|---|
| `CI_ENVIRONMENT` | Use `production` when deployed |
| `app.baseURL` | Public HTTPS URL with trailing slash |
| `app.appTimezone` | Defaults to `Asia/Manila` |
| `app.forceGlobalSecureRequests` | Enable behind a correctly configured HTTPS proxy |
| `database.default.hostname` | MySQL-compatible host |
| `database.default.port` | Usually `3306` |
| `database.default.database` | Database name |
| `database.default.username` | Database user |
| `database.default.password` | Database secret |
| `database.default.DBDriver` | `MySQLi` |
| `database.default.encrypt` | Database TLS setting |
| `MIGRATE_ON_START` | Run migrations at container startup |
| `SEED_ON_START` | Seed at startup; keep false after initial setup |

Keep secrets in environment variables, never source control.

## Routes and behavior

Public GET routes are `/`, `/tasks`, `/profile`, `/about`, `/login`, and `/health`. Login is a public POST. Logout is an authenticated POST. New, create, edit, update, and archive task routes require authentication.

Archiving sets `is_archived` to `1`; it never deletes the row. Archived records are excluded by the model’s public queries.

## Tests

Configure CodeIgniter’s `tests` database group with a disposable MySQL/MariaDB database, then run:

```bash
composer test
```

The suite covers public access, today filtering, archive exclusion, ordering, profile safety, authentication, validation, task lifecycle, protected routes, and missing IDs. The application’s global filter integration enforces CSRF on state-changing routes.

## Docker

```bash
docker build -t tasks-for-today .
docker run --rm -p 8080:8080 --env-file .env tasks-for-today
```

The image listens on port 8080, serves `public/`, and prepares `writable/` permissions.

For a complete local environment with MariaDB and seeded demo data:

```bash
docker compose up --build -d
docker compose ps
```

Open `http://localhost:8080`. Stop the environment with `docker compose down`. Add `-v` only when you intentionally want to remove the local database volume and start fresh.

## Render

1. Provision an external MySQL-compatible database.
2. Create a Docker Web Service from this repository. `render.yaml` sets `/health` as the health check.
3. Add the production variables above and set `app.baseURL` to the Render HTTPS URL.
4. Set `MIGRATE_ON_START=true`. Use `SEED_ON_START=true` only for the first demo deployment, then set it to `false`.
5. Docker supplies the build and start commands. Verify `/health`, public pages, login, create, edit, archive, and logout.

Render may require an external MySQL provider depending on plan and region.

## Clever Cloud

1. Create a Docker application and attach a MySQL add-on or external database.
2. Copy the add-on values into the `database.default.*` variables.
3. Set `app.baseURL` to the Clever Cloud HTTPS domain and configure the other production variables.
4. Use the Dockerfile for build and startup. The container binds to 8080.
5. Run migrations with `MIGRATE_ON_START=true`, seed once only when demo data is wanted, and configure `/health` for checks.
6. Verify every public and authenticated workflow.

## Troubleshooting

- Database failure: verify network access, host, port, credentials, and TLS.
- HTTPS redirect loop: verify forwarded scheme handling before forcing HTTPS.
- Writable error: confirm the web user can write to `writable/`.
- CSRF rejection: reload the form and confirm session persistence.
- No tasks today: seed again in a disposable environment; dates are relative to seed time.

For production, enable production mode, set HTTPS and proxy handling, migrate before traffic, disable repeated seeding, review logs, and replace the demo password.
