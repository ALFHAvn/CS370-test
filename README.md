# SocialNet (Mock Web Application)

PHP + MySQL project matching the assignment URL layout. **Tech stack for deployment:** PHP, MySQL, Nginx, Linux (see below).

## Endpoints

| Page | URL |
|------|-----|
| Admin (create users) | `/admin/newuser.php` |
| Sign in | `/socialnet/signin.php` |
| Home | `/socialnet/index.php` |
| Profile | `/socialnet/profile.php` (optional `?owner=username`) |
| Setting | `/socialnet/setting.php` |
| About | `/socialnet/about.php` |
| Sign out | `/socialnet/signout.php` |

After a successful sign-in, the user is sent to **Home** (`/socialnet/index.php`). Sign out clears the session and redirects to **Sign in**.

## Database

`db.sql` creates:

- **Database:** `socialnet`
- **Table:** `account` — columns `id`, `username`, `fullname`, `password`, `description`

Import (adjust user as needed):

```bash
mysql -u root -p < db.sql
```

Create an application MySQL user with access to `socialnet` (example):

```sql
CREATE USER IF NOT EXISTS 'webuser'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON socialnet.* TO 'webuser'@'localhost';
FLUSH PRIVILEGES;
```

## Configuration (DB connection)

Environment variables (optional; defaults match a typical local setup):

| Variable | Default |
|----------|---------|
| `SOCIALNET_DB_HOST` | `localhost` |
| `SOCIALNET_DB_USER` | `webuser` |
| `SOCIALNET_DB_PASS` | `123456` |
| `SOCIALNET_DB_NAME` | `socialnet` |

## Running (Nginx + PHP-FPM on Linux)

1. Install **nginx**, **mysql-server**, **php-fpm**, **php-mysql**, and **php-mbstring** (required — the app uses `mb_strlen`).
   - Example (PHP 8.3): `sudo apt install nginx mysql-server php8.3-fpm php8.3-mysql php8.3-mbstring`
2. Point the Nginx `root` at this repository directory (e.g. `/var/www/socialnet`).
3. Pass PHP to the matching FPM socket, e.g. `unix:/run/php/php8.3-fpm.sock`.
4. Import `db.sql` and grant the DB user as above.
5. Test: `/admin/newuser.php` → create users → `/socialnet/signin.php`.

## About page (static content)

Student details are set in **`socialnet/about.php`**:

- **Name:** Nguyen Dinh Toan Thang  
- **Student number:** 1694559  

Change that file if your submission must use different details.

## Extensions (beyond the minimum spec)

These are intentional extras, not required by the assignment brief:

- **CSRF tokens** on POST forms (admin new user, sign-in, settings).
- **Session hardening:** `HttpOnly` cookies, `SameSite=Lax`, `session_regenerate_id(true)` after successful login.
- **Output escaping** via `htmlspecialchars` for displayed user data (reduces XSS risk).
- **Prepared statements** for SQL.
- **Configurable DB** via environment variables instead of hardcoding credentials in each page.

## Quick test checklist

1. Create two users via `/admin/newuser.php`.
2. Sign in as user A at `/socialnet/signin.php` → should land on Home with username and full name.
3. Home lists other users; open another user’s profile via the link (`?owner=...`).
4. Edit description on `/socialnet/setting.php`; confirm on `/socialnet/profile.php`.
5. `/socialnet/about.php` shows the static student lines and the menubar.
6. Sign out → should require sign-in again to open Home.
