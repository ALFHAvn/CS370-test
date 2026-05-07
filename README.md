# SocialNet (Mock Web Application)

PHP + MySQL project implementing the required endpoints:

- `/admin/newuser.php`: admin creates user accounts
- `/socialnet/signin.php`: sign in (redirects to Home on success)
- `/socialnet/index.php`: Home (requires login) + list other users
- `/socialnet/profile.php`: Profile (optional `?owner=some_user`)
- `/socialnet/setting.php`: edit profile content (`description`)
- `/socialnet/about.php`: static student info
- `/socialnet/signout.php`: sign out and redirect

## Database

This repo includes `db.sql` to create the required database and table:

- **DB name**: `socialnet`
- **Table**: `account` with columns `id`, `username`, `fullname`, `password`, `description`

Create DB + table:

```bash
mysql -u root -p < db.sql
```

## Configuration (DB connection)

The app reads DB settings from environment variables (with defaults):

- `SOCIALNET_DB_HOST` (default `localhost`)
- `SOCIALNET_DB_USER` (default `webuser`)
- `SOCIALNET_DB_PASS` (default `123456`)
- `SOCIALNET_DB_NAME` (default `socialnet`)

## Running (Nginx + PHP-FPM on Linux)

Typical setup:

- Install packages: `nginx`, `php-fpm`, `php-mysql`, `mysql-server`
- Configure an Nginx site root pointing to this repo directory
- Ensure PHP is handled via PHP-FPM
- Import `db.sql`
- Open `/admin/newuser.php` to create an account, then sign in at `/socialnet/signin.php`

## Required static info

Update your student info here:

- `socialnet/about.php`: replace `YOUR_NAME_HERE` and `YOUR_STUDENT_NUMBER_HERE`
