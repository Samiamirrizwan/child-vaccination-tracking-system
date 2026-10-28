# Database files

`cits1.sql` is the canonical **schema-only** database export for local setup. Its table definitions are retained, but all `INSERT` statements were removed before publishing so sample account credentials, child records, contact details, and appointment data are not distributed as repository seed data.

Create a database named `cits1` first, then import this file. See [`../SETUP_AND_DEPLOYMENT.md`](../SETUP_AND_DEPLOYMENT.md) for phpMyAdmin, command-line, local, and production instructions.

Do not commit real database exports, health/child information, application passwords, or production data. Any migration dump should be reviewed separately and stored securely outside Git.
