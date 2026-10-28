# CVTS Setup, Database, Git, and Deployment Guide

This guide covers local development, database preparation, publishing the source to GitHub, and the operational pieces of a production-style deployment. **Important:** the current CVTS source is a legacy educational prototype with known authentication, SQL-injection, and PHP-compatibility issues. Do not place real child/health data in it or expose it publicly until those issues have been fixed and reviewed.

## 1. Requirements

- Apache (or another PHP-capable web server)
- PHP with `mysqli` and `pdo_mysql` enabled
- MySQL or MariaDB
- A browser and Git
- For Windows development, XAMPP is a convenient local option; Linux users can use Apache/PHP/MySQL or MariaDB packages.

The source tree includes most frontend libraries. A root Composer or npm installation is not required by the supplied project.

## 2. Local development setup

### Step 1 — Place the project in the web root

For XAMPP, extract/clone the project to a folder such as:

```text
C:\xampp\htdocs\cvts-final\
```

For a typical Linux Apache install, use the configured document root (often `/var/www/html/cvts-final/`). The project’s landing file is `home.php`, not `index.php`, so open it directly unless you configure `home.php` as the directory index.

### Step 2 — Create the database

Create a database called `cits1`. The SQL dump uses this as the canonical application database name, even though the original dump header used a different folder/project label.

Using phpMyAdmin:

1. Open `http://localhost/phpmyadmin`.
2. Create a database named `cits1` with a Unicode collation such as `utf8mb4_unicode_ci`.
3. Select `cits1`, open **Import**, choose `db/cits1.sql`, and start the import.

Or use the MySQL command-line client from a shell that supports input redirection:

```bash
mysql -u root -p -e "CREATE DATABASE cits1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p cits1 < db/cits1.sql
```

If your local MySQL `root` account has no password, omit `-p` or follow the prompts that match your local setup. Prefer a dedicated database user for the application rather than using `root`.

Example development-only database user (change the password before running):

```sql
CREATE USER 'cvts_dev'@'localhost' IDENTIFIED BY 'choose-a-local-password';
GRANT ALL PRIVILEGES ON cits1.* TO 'cvts_dev'@'localhost';
FLUSH PRIVILEGES;
```

Run these statements using a database administrator account. The broad grant is for a disposable local development database; use narrower privileges for production.

### Step 3 — Configure database credentials

Copy the template to a local-only config file.

**Windows PowerShell:**

```powershell
Copy-Item config.local.example.php config.local.php
```

**Linux/macOS/Git Bash:**

```bash
cp config.local.example.php config.local.php
```

Edit `config.local.php` with your local database host, user, password, and database name:

```php
define('DB_SERVER', '127.0.0.1');
define('DB_USER', 'cvts_dev');
define('DB_PASS', 'your-local-database-password');
define('DB_NAME', 'cits1');
```

Keep the surrounding PHP tags from the supplied template. `config.local.php` is ignored by Git; do not force-add it or commit credentials. If you prefer to use environment variables, the shared bootstrap also reads `CVTS_DB_HOST`, `CVTS_DB_USER`, `CVTS_DB_PASS`, and `CVTS_DB_NAME`.

The configuration is shared by the root, parent, admin, and health-officer entry points. The legacy `cits/include/conn.php` and PDO wrapper use the same settings so that they no longer point at a different database name.

### Step 4 — Open the application

If your project folder is named `cvts-final`, try these local routes:

| Area | URL |
|---|---|
| Public home | `http://localhost/cvts-final/home.php` |
| About | `http://localhost/cvts-final/about.php` |
| Contact | `http://localhost/cvts-final/contact.php` |
| Parent registration | `http://localhost/cvts-final/cits/registration.php` |
| Parent login | `http://localhost/cvts-final/cits/user-login.php` |
| Admin login | `http://localhost/cvts-final/cits/admin/index.php` |
| Health-officer login | `http://localhost/cvts-final/cits/healthofficer/index.php` |

Replace `cvts-final` with the folder name you actually used. The included SQL is **schema-only** and intentionally has no seeded administrator, parent, health-officer, or child records. The parent registration page can be used for local testing after the known registration issues are fixed.

### Step 5 — Local administrator seed (development only)

The original export’s built-in admin row was intentionally removed, so the public database file does not publish a default credential. For a disposable local database only, you can create an administrator row with a unique, local-only password using phpMyAdmin or the MySQL client:

```sql
INSERT INTO admin (username, password, updationDate)
VALUES ('local-admin', 'replace-with-a-local-only-password', NOW());
```

This statement reflects the current legacy login implementation, which compares the administrator password as plain text. **Do not use this pattern for a public or production system.** Before production, fix authentication to use `password_hash()` / `password_verify()` and build a secure admin-provisioning process.

## 3. Database setup for a production environment

These are database and configuration steps only; they do not resolve the security problems listed in the README. Do not launch CVTS with real user, child, vaccination, or health records until the application has been remediated and independently tested.

### Step 1 — Provision a dedicated database

On the database server, create the application database and a non-root account. Run this as a database administrator and replace the sample password with a strong unique secret:

```sql
CREATE DATABASE cits1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cvts_app'@'localhost' IDENTIFIED BY 'replace-with-a-strong-unique-secret';
GRANT SELECT, INSERT, UPDATE, DELETE ON cits1.* TO 'cvts_app'@'localhost';
FLUSH PRIVILEGES;
```

Restrict the database account to the actual application host. If the web app and database are on different machines, replace `localhost` with a narrowly scoped source host or private-network address; do not expose MySQL to the public internet. The runtime grant above assumes the schema is imported separately by an administrator.

Import the schema from a trusted deployment workspace:

```bash
mysql -u db_admin -p cits1 < db/cits1.sql
```

Do not import old database backups or copy production data into a development repository. If you must migrate existing data, review it separately, minimize the fields transferred, and keep the export encrypted and outside Git.

### Step 2 — Supply production configuration without committing secrets

The shared `config.php` supports either environment variables or a private PHP config file:

- Set `CVTS_ENV=production`.
- Set `CVTS_DB_HOST`, `CVTS_DB_USER`, `CVTS_DB_PASS`, and `CVTS_DB_NAME` in the PHP application process environment; use `cits1` for the database name.
- Alternatively, set `CVTS_CONFIG_FILE` to the absolute path of a private PHP file that defines `DB_SERVER`, `DB_USER`, `DB_PASS`, and `DB_NAME`. Keep that file outside the public web root and out of the repository.

Example variable names (do not paste real secrets into documentation, source control, issue trackers, or shell history):

```text
CVTS_ENV=production
CVTS_DB_HOST=127.0.0.1
CVTS_DB_NAME=cits1
CVTS_DB_USER=cvts_app
CVTS_DB_PASS=<set securely on the server>
```

Configure variables in a server-managed secret store, the Apache virtual-host configuration, or the PHP-FPM pool configuration as appropriate for your host. Verify that the PHP worker receives them; shell variables set in an interactive terminal are not automatically guaranteed to reach Apache/PHP-FPM. Restart/reload the relevant service after changing server configuration. The production bootstrap intentionally fails with a generic error if required environment variables are absent and no private config file is supplied.

### Step 3 — Web server and deployment basics

- Serve the project over HTTPS only and redirect HTTP to HTTPS.
- Point the virtual host to the project directory; set `home.php` as the default directory index if needed, and disable directory listing.
- Keep database ports private and the database user least-privileged.
- Set PHP to log errors privately rather than displaying stack traces or database details to visitors.
- Keep uploads, logs, database dumps, and backups out of the public repository; protect backups at rest and test recovery.
- Use separate development, staging, and production databases. Do not test with real user/child data in development.
- Monitor PHP/MySQL versions and patch the operating system, server, PHP extensions, and database.

## 4. Publishing the project to a new GitHub repository

### Step 1 — Create an empty repository

On GitHub, create a new repository. For the cleanest first push, leave **Add a README**, **Add .gitignore**, and **Choose a license** unchecked because this project folder already contains a README and `.gitignore`. Choose a license only after confirming you have the rights to license the source and bundled third-party assets.

### Step 2 — Review the folder before Git stages it

Open a terminal in the project root and check the ignored/config files:

```bash
git status --short
```

Confirm that `config.local.php`, credentials, logs, and personal/production database exports are not staged. The public `db/cits1.sql` contains schema only; do not replace it with an unreviewed dump from a live environment.

### Step 3 — Initialize and push

Run these commands from the root of the project. Replace the remote URL with the repository you created:

```bash
git init -b main
git add .
git status --short
git commit -m "Initial CVTS project upload"
git remote add origin https://github.com/YOUR-USERNAME/YOUR-REPOSITORY.git
git push -u origin main
```

If Git reports that the branch already exists or a repository is already initialized, do not blindly repeat the initialization; inspect `git status`, `git branch`, and `git remote -v` first. If you initialized the GitHub repository with a README, pull/reconcile that history before pushing.

### Step 4 — If a secret was committed by mistake

Removing a secret from the latest file is not enough if it already exists in Git history. Revoke/rotate the affected credential immediately, then purge the exposed secret from history before publishing or sharing the repository. Treat any exposed production database password or account credential as compromised.

## 5. Common setup problems

| Problem | What to check |
|---|---|
| Database connection failed | MySQL/MariaDB is running; `cits1` exists; `config.local.php` has the correct host/user/password; `mysqli` is enabled. |
| Unknown database `cits1` | Create the database first, then import `db/cits1.sql` into it. |
| `home.php` does not open at `/` | Open `/home.php` directly or configure the web server’s directory index. |
| Admin login has no working credentials | The public dump intentionally contains no admin seed. Use the local-only seed procedure above for a disposable database, and remember that the current password handling is insecure. |
| `mysql_query()` is undefined | `cits/admin/registration.php` contains a removed legacy PHP API. Replace it with MySQLi/PDO prepared statements; do not switch a public server to obsolete PHP. |
| A feature fails after login | Check PHP’s private error log, inspect the route and required tables, and test with non-sensitive data. Some documented workflows may be incomplete in this legacy project. |

## 6. Recommended work before a real release

1. Audit every SQL query and migrate to prepared statements.
2. Standardize account/password handling with `password_hash()` and `password_verify()`.
3. Repair legacy PHP APIs and test on a supported PHP version.
4. Validate authorization for every admin, parent, and health-officer action.
5. Add CSRF protection, input validation, output escaping, rate limiting, secure session settings, audit logging, and tested backup/restore procedures.
6. Review privacy obligations and define retention, access, deletion, and incident-response processes for child and health information.
7. Run automated and manual tests for registration, login, child profiles, scheduling, booking, status changes, reports, and contact forms.
