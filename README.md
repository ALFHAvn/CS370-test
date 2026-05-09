# SocialNet Project

PHP + MySQL web app for the SocialNet mock assignment. **Intended stack:** Linux (Ubuntu), **Nginx**, **PHP-FPM**, **MySQL**.

This README walks through setup **from cloning the repository** on a fresh Ubuntu machine through opening the site in a browser.

---

## Endpoints (for grading)

| Page | URL |
|------|-----|
| Admin (create users) | `/admin/newuser.php` |
| Sign in | `/socialnet/signin.php` |
| Home | `/socialnet/index.php` |
| Profile | `/socialnet/profile.php` (optional `?owner=username`) |
| Setting | `/socialnet/setting.php` |
| About | `/socialnet/about.php` |
| Sign out | `/socialnet/signout.php` |

After sign-in, users go to **Home**. Sign out sends them back to **Sign in**.

---

## What you need before starting

- A machine or VM with **Ubuntu** (22.04 or 24.04 is fine).
- **sudo** access.
- Internet to install packages and clone Git.
- This project’s repo: **`https://github.com/ALFHAvn/CS370-test.git`**

All commands below are run in a terminal on that Ubuntu system.

---

## Part 1 — Clone the project

### 1.1 Install Git (if you don’t have it)

```bash
sudo apt update
sudo apt install -y git
```

### 1.2 Clone into your home folder

```bash
cd ~
git clone https://github.com/ALFHAvn/CS370-test.git socialnet-app
cd socialnet-app
```

You should see folders like `admin/`, `socialnet/`, `includes/`, and the file `db.sql`.

---

## Part 2 — Put files where the web server can read them

Nginx is usually configured to serve from **`/var/www/...`**. Copy your clone there (recommended for beginners):

```bash
sudo mkdir -p /var/www/socialnet
sudo rsync -a --delete ~/socialnet-app/ /var/www/socialnet/
sudo chown -R www-data:www-data /var/www/socialnet
```

- **`rsync`** keeps `/var/www/socialnet` in sync with your project folder. After you `git pull` new changes in `~/socialnet-app`, run the same `rsync` and `chown` lines again to update the live site.
- **`www-data`** is the user Nginx/PHP-FPM use to read files on Ubuntu.

From here on, paths assume the project lives at **`/var/www/socialnet`**.

---

## Part 3 — Install MySQL, Nginx, and PHP

### 3.1 Install packages

```bash
sudo apt update
sudo apt install -y nginx mysql-server
```

Install PHP-FPM and extensions. Ubuntu ties package names to the PHP version. Check yours:

```bash
php -v
```

If you see **PHP 8.3**, install:

```bash
sudo apt install -y php8.3-fpm php8.3-mysql php8.3-mbstring
```

If you see **8.1** or **8.2**, replace `8.3` in those package names (e.g. `php8.2-fpm`).

**Important:** **`php-*-mbstring`** is required. Without it, creating a user on the admin page can return **HTTP 500** (`mb_strlen` missing).

### 3.2 Find your PHP-FPM socket path

Nginx must forward `.php` requests to PHP-FPM using a **socket file**:

```bash
ls /run/php/
```

Look for a name like **`php8.3-fpm.sock`**. You will paste that exact name into the Nginx config in Part 5.

---

## Part 4 — Create the database and MySQL user

### 4.1 Import `db.sql`

This creates database **`socialnet`** and table **`account`**.

**Option A — MySQL root has a password**

```bash
sudo mysql -u root -p < /var/www/socialnet/db.sql
```

Enter the root password when asked.

**Option B — `mysql -u root -p` fails on Ubuntu**

Some installs only allow root via `sudo`:

```bash
sudo mysql < /var/www/socialnet/db.sql
```

### 4.2 Create the application user the PHP code uses

The app defaults to user **`webuser`**, password **`123456`**, database **`socialnet`**.

Open MySQL as admin (pick one that works on your machine):

```bash
sudo mysql
```

or:

```bash
mysql -u root -p
```

Then paste and run:

```sql
CREATE USER IF NOT EXISTS 'webuser'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON socialnet.* TO 'webuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Test** that `webuser` works:

```bash
mysql -u webuser -p -e "USE socialnet; SHOW TABLES;"
```

Password: `123456`. You should see the `account` table.

---

## Part 5 — Configure Nginx

### 5.1 Create a site config file

```bash
sudo nano /etc/nginx/sites-available/socialnet
```

Paste the block below. **Change only the `fastcgi_pass` line** so the socket matches what you saw in `ls /run/php/` (example uses PHP 8.3):

```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    server_name _;

    root /var/www/socialnet;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
}
```

Save and exit (in nano: **Ctrl+O**, Enter, **Ctrl+X**).

### 5.2 Enable this site and disable the default (avoids wrong `root` / 404)

```bash
sudo rm -f /etc/nginx/sites-enabled/default
sudo ln -sf /etc/nginx/sites-available/socialnet /etc/nginx/sites-enabled/socialnet
```

### 5.3 Test config and reload Nginx

```bash
sudo nginx -t
sudo systemctl reload nginx
```

If `nginx -t` prints an error, fix the file and run it again before reloading.

### 5.4 Restart PHP-FPM (after installs or config changes)

Use the service name that matches your PHP version:

```bash
sudo systemctl restart php8.3-fpm
```

(Use `php8.2-fpm` or `php8.1-fpm` if that is what you installed.)

---

## Part 6 — Open the app in a browser

1. Find the server’s IP (on the VM):

   ```bash
   hostname -I
   ```

2. On your PC, open (replace with your IP):

   - `http://YOUR_SERVER_IP/admin/newuser.php` — create at least two users  
   - `http://YOUR_SERVER_IP/socialnet/signin.php` — sign in  

If the page does not load, check **firewall** (if enabled, allow HTTP):

```bash
sudo ufw allow 'Nginx HTTP'
sudo ufw reload
```

---

## Part 7 — Optional: database settings via environment variables

Defaults are built into `includes/bootstrap.php` (`webuser` / `123456` / `socialnet`). To override without editing code, you can pass variables into PHP-FPM (advanced). Names:

| Variable | Default |
|----------|---------|
| `SOCIALNET_DB_HOST` | `localhost` |
| `SOCIALNET_DB_USER` | `webuser` |
| `SOCIALNET_DB_PASS` | `123456` |
| `SOCIALNET_DB_NAME` | `socialnet` |

Example for PHP 8.3: edit the pool file (path may vary):

```bash
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
```

Inside the `[www]` section, add lines like:

```ini
env[SOCIALNET_DB_USER] = webuser
env[SOCIALNET_DB_PASS] = your_secret_password
```

Then:

```bash
sudo systemctl restart php8.3-fpm
```

You must create matching MySQL credentials with `GRANT`.

---

## Database summary (`db.sql`)

- **Database:** `socialnet`
- **Table:** `account` — columns `id`, `username`, `fullname`, `password`, `description`

---

## About page (static content)

Student details are in **`socialnet/about.php`**:

- **Name:** Nguyen Dinh Toan Thang  
- **Student number:** 1694559  

---

## Extensions (beyond the minimum spec)

- CSRF tokens on POST forms  
- Session cookie flags + `session_regenerate_id` after login  
- Output escaping for displayed text  
- Prepared statements for SQL  
- Optional DB settings via environment variables  

---

## Quick test checklist

1. Create two users via `/admin/newuser.php`.  
2. Sign in as the first user → Home shows username and full name.  
3. Home lists the other user; open their profile (`?owner=...`).  
4. Change description in Settings; confirm on Profile.  
5. About page shows student info and the menu bar.  
6. Sign out → Home should require sign-in again.  

---

## Troubleshooting (common issues)

| Symptom | What to check |
|--------|----------------|
| **404** on `/admin/...` | Nginx `root` must be `/var/www/socialnet` (project root with `admin/` inside). Remove `default` from `sites-enabled` if it still wins. Run `sudo nginx -t`. |
| **502 Bad Gateway** | Wrong `fastcgi_pass` socket — run `ls /run/php/` and match PHP version; `sudo systemctl status php8.3-fpm`. |
| **500** on admin “Create user” | Install **`php-mbstring`** for your PHP version and restart PHP-FPM. Check `sudo tail -n 40 /var/log/nginx/error.log`. |
| **Database connection failed** / blank error | Create `webuser` and `GRANT` on `socialnet`. Test with `mysql -u webuser -p`. |
| **403** on some paths | `sudo chown -R www-data:www-data /var/www/socialnet` |

---

## Updating the site after `git pull`

```bash
cd ~/socialnet-app
git pull
sudo rsync -a --delete ~/socialnet-app/ /var/www/socialnet/
sudo chown -R www-data:www-data /var/www/socialnet
```

No need to re-import `db.sql` unless you intentionally changed the schema.
