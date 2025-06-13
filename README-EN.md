# 🔗 URL Shortener + QR Code – Yii2 Technical Test

This project is a minimalist web service that allows:

- Shortening long URLs
- Generating a local QR code for each short link
- Redirecting to the original URL using a short code
- Tracking clicks (IP + counter)
- All without any external API call

---

## ⚙️ Technologies Used

- PHP 8+
- Yii2 Framework (basic template)
- MySQL / MariaDB
- jQuery + Bootstrap
- Local QR Code generation (`endroid/qr-code` version 6.x)

---

## ⚙️ Installation & Setup

### 1. Prerequisites

Make sure you have installed:

- PHP ≥ 8.0
- Composer ≥ 2.0
- MySQL or MariaDB
- Web browser
- (Optional) Local server like Laragon, XAMPP, etc.

---

### 2. Clone the project and install dependencies

```bash
git clone https://github.com/ThuretGbenougan/URL-Shortener-QR-Code---Yii2
cd URL-Shortener-QR-Code---Yii2
composer install
```

---

### 3. Create the database

In your DBMS (phpMyAdmin, console or other):

```sql
CREATE DATABASE shorturl_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### 4. Configure database connection

Edit the file `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=shorturl_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

---

### 5. Apply database migrations

#### ✅ Option A: Use Yii2 migration files

```bash
php yii migrate
```

#### ✅ Option B: Import the provided SQL dump (`schema.sql`)

```sql
-- Import via phpMyAdmin or database tool
-- or via command line:
mysql -u root -p shorturl_db < schema.sql
```

---

### 6. Run the application

From the root of the project:

```bash
php yii serve
```

Then visit:  
👉 `http://localhost:8080`

---

### 7. Test

1. Open the home page
2. Enter a valid URL
3. Click **OK**
4. You'll see:
   - A QR code
   - A shortened link
   - A "Copy" button
5. Try visiting the short link `/u/abc123`

All clicks are logged with IP and timestamp.

---

## 🗃️ Database Tables

### Table `url`

| Column         | Type        | Description                     |
|----------------|-------------|---------------------------------|
| `id`           | int         | Primary key                     |
| `original_url` | text        | Original full URL               |
| `short_code`   | varchar(16) | Unique short code (ex: `abc123`)|
| `created_at`   | timestamp   | Auto timestamp                  |
| `clicks`       | int         | Total number of redirects       |

### Table `url_log`

| Column       | Type      | Description                |
|--------------|-----------|----------------------------|
| `id`         | int       | Primary key                |
| `url_id`     | int       | Foreign key to `url` table |
| `ip_address` | varchar   | Visitor's IP address       |
| `visited_at` | timestamp | Time of the click          |

---

## 📂 Additional Notes

- QR codes are saved locally in: `/web/qr/`
- Yii2 error logs: `/runtime/logs/app.log`
- Fully local — no external APIs used
- Responsive UI thanks to Bootstrap

---