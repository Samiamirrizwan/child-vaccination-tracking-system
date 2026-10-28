# CVTS — Child Vaccination Tracking System

CVTS is a PHP and MySQL web application prototype for organizing child vaccination and appointment information. The project was designed to move away from a fully manual process and give parents and staff a shared place to maintain records, review schedules, and follow appointment status.

This repository contains the source code, the project documentation, and a **schema-only** database export. I’ve kept the setup practical for local development while separating database credentials from the files tracked by Git.

> **Project status:** CVTS is an educational/legacy prototype, not a production-ready healthcare system. Read the security notes below before putting it on a public server or entering real child, parent, or health data.

## What the project covers

The supplied project documentation describes three main roles:

- **Administrator:** review child and vaccination information, view reports and bookings, manage requests, and maintain the hospital/health-provider list.
- **Parent:** register and sign in, maintain child details, view vaccination/appointment information, and request or book an appointment.
- **Health provider:** sign in and work with appointment or vaccination status. The project document calls this role “Hospital”; the source folders use the legacy name `healthofficer` and some database tables retain older doctor/appointment terminology.

The public pages include a home page, About page, and Contact page. The current source also contains legacy medical-appointment components, so the documented feature list should be treated as the intended project scope rather than a guarantee that every workflow is complete or secure.

## Technology

- **Backend:** PHP using procedural MySQLi, with a legacy PDO wrapper in the admin area
- **Database:** MySQL or MariaDB
- **Frontend:** HTML, CSS, JavaScript, Bootstrap, jQuery, and Font Awesome assets
- **Local development:** Apache with PHP and a MySQL-compatible database (for example, XAMPP on Windows or a LAMP stack on Linux)

There is no root-level Composer or Node build step in the supplied project. Most frontend assets are already included in the source tree.

## Repository layout

```text
.
├── home.php                       # Public landing page
├── about.php                      # About page
├── contact.php                    # Contact form
├── config.php                     # Shared DB bootstrap (no committed secrets)
├── config.local.example.php       # Template for local-only DB settings
├── cits/
│   ├── registration.php           # Parent/user registration
│   ├── user-login.php             # Parent/user login
│   ├── dashboard.php              # Parent/user dashboard
│   ├── admin/                     # Admin pages
│   ├── healthofficer/             # Health-provider pages
│   └── include/                   # Shared includes and legacy CRUD pages
├── db/
│   ├── cits1.sql                  # Schema-only SQL export; no sample rows
│   └── README.md                  # Database dump notes
├── Documentation/
│   └── Documentation CVTS.docx    # Supplied project documentation
├── assets/, css/, fonts/, images/, js/, home/
└── SETUP_AND_DEPLOYMENT.md        # Local, database, Git, and deployment guide
```

## Quick start

1. Install and start Apache/PHP plus MySQL or MariaDB.
2. Create a database named `cits1` and import [`db/cits1.sql`](db/cits1.sql).
3. Copy `config.local.example.php` to `config.local.php` and edit the database username and password. The local configuration file is ignored by Git.
4. Put the project folder under your web server’s document root and open `home.php` in your browser. With XAMPP, a folder named `cvts-final` will normally be available at `http://localhost/cvts-final/home.php`.

The detailed setup, database import commands, first-run notes, Git commands, and production configuration are in **[SETUP_AND_DEPLOYMENT.md](SETUP_AND_DEPLOYMENT.md)**.

## Database notes

The canonical database name used by the shared configuration is `cits1`. The original SQL file mixed schema definitions with sample users, child details, appointment records, and an example administrator credential. For public-repository hygiene, the exported `db/cits1.sql` has been reduced to schema statements only. You will need to create your own local/test data after importing it. No default administrator login is provided.

## Security and production readiness

Please do not use this project with real patients or children’s data without a security review and remediation. The legacy source includes direct SQL string interpolation, inconsistent password handling (including plain-text comparisons and MD5 usage), and at least one route using the removed `mysql_*` API. These issues can expose accounts and sensitive records and may cause compatibility failures on modern PHP versions.

Before a real deployment, at minimum:

- Replace interpolated SQL with prepared statements throughout the application.
- Use `password_hash()` and `password_verify()` consistently; remove plain-text and MD5 password handling.
- Fix the legacy `mysql_query()` call and test the application on a supported current PHP release. Do not solve compatibility by deploying an obsolete PHP version.
- Add CSRF protection, robust validation, secure session/cookie settings, role-based access checks, rate limiting, and safe error handling.
- Enforce HTTPS, keep the database private, use a least-privilege database account, protect backups, and define a privacy/data-retention policy.
- Test all role-specific workflows and database operations with non-sensitive test records.

The deployment section explains environment and database setup, but those steps alone do **not** make the current application safe for production.

## Contributing / reporting issues

Small, focused changes are easier to review. Please avoid committing database exports containing real records, local configuration files, credentials, logs, or user-uploaded health information. For bugs, include the route, PHP/MySQL version, steps to reproduce, and a redacted error message—never a live password or patient record.
