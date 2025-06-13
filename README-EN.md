# 🔗 URL Shortener + QR Code – Yii2 Technical Test

This web service allows:

- Shortening long URLs
- Generating local QR codes
- Redirecting to the original URL with IP tracking
- Viewing click statistics
- Displaying a table of all previously created short links

---

## ⚙️ Technologies Used

- PHP 8+
- Yii2 Framework (basic template)
- MySQL / MariaDB
- jQuery + Bootstrap
- `endroid/qr-code` v6.x (local generation)

---

## 🚀 Features

- URL syntax validation and availability check
- Short URL + QR code generation
- Ajax-based, no page reload
- QR download button
- Copy short link to clipboard
- Redirection via `/u/<code>` with IP logging
- Statistics available at `/stats/<code>`
- History table of all previously generated links

---

## ⚙️ Installation

### 1. Clone the project

```bash
git clone https://github.com/ThuretGbenougan/URL-Shortener-QR-Code---Yii2
cd URL-Shortener-QR-Code---Yii2
composer install
```

### 2. Create and configure the database

```sql
CREATE DATABASE shorturl_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=shorturl_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

### 3. Run migrations

```bash
php yii migrate
```

### 4. Enable Pretty URLs

In `config/web.php`:

```php
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'u/<code>' => 'site/redirect',
        'stats/<code>' => 'site/stats',
    ],
],
```

Make sure you have `.htaccess` in `/web` for Apache.

---

## 🧪 Test

- Go to homepage: `http://localhost`
- Enter a URL → view QR, copy & download buttons
- Click 📊 Statistics button
- See a full table of previous links below

---

## 🗃️ Database Structure

### `url` table:

- `id`, `original_url`, `short_code`, `created_at`, `clicks`

### `url_log` table:

- `id`, `url_id`, `ip_address`, `visited_at`

---

## Notes

- QR codes saved in `/web/qr/`
- Logs available at `/runtime/logs/app.log`
