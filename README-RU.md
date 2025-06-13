# 🔗 Сокращатель ссылок + QR-код — Тестовое задание Yii2

Этот проект позволяет:

- Сокращать длинные ссылки
- Генерировать локальный QR-код
- Перенаправлять на исходную ссылку с отслеживанием IP
- Смотреть статистику переходов
- Видеть таблицу всех созданных ссылок

---

## ⚙️ Технологии

- PHP 8+
- Yii2 (basic template)
- MySQL / MariaDB
- jQuery + Bootstrap
- `endroid/qr-code` v6.x (локально)

---

## 🚀 Возможности

- Проверка формата и доступности URL
- Генерация короткой ссылки и QR-кода
- Интерфейс без перезагрузки (Ajax)
- Кнопка скачивания QR-кода
- Кнопка копирования ссылки
- Редирект через `/u/<code>` с записью IP
- Статистика через `/stats/<code>`
- История всех ранее созданных ссылок в таблице

---

## ⚙️ Установка

### 1. Клонировать репозиторий

```bash
git clone https://github.com/ThuretGbenougan/URL-Shortener-QR-Code---Yii2
cd URL-Shortener-QR-Code---Yii2
composer install
```

### 2. Создать и настроить базу данных

```sql
CREATE DATABASE shorturl_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Файл `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=shorturl_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

### 3. Применить миграции

```bash
php yii migrate
```

### 4. Включить "красивые URL"

В `config/web.php`:

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

Файл `.htaccess` обязателен при использовании Apache.

---

## 🧪 Проверка

- Перейти на главную страницу: `http://localhost`
- Ввести URL → QR, кнопки «копировать» и «скачать»
- Нажать 📊 «Посмотреть статистику»
- Ниже — таблица всех ссылок

---

## 🗃️ Структура БД

### Таблица `url`

- `id`, `original_url`, `short_code`, `created_at`, `clicks`

### Таблица `url_log`

- `id`, `url_id`, `ip_address`, `visited_at`

---

## 📂 Примечания

- QR-коды хранятся в `/web/qr/`
- Логи: `/runtime/logs/app.log`
