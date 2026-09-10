# PHP URL Shortener

A lightweight URL shortening application built with PHP and MySQL. The application converts long URLs into short, shareable links and supports custom aliases, URL expiration, reserved aliases, duplicate-alias protection, and click tracking.

## Live Demo

**https://urlshortener.42web.io/**

> The live demo is hosted on InfinityFree.

## Features

* Generate random short URLs
* Create custom aliases
* Block reserved aliases
* Set optional URL expiration
* Track the number of clicks
* Redirect short URLs to their original destinations
* Responsive user interface
* Apache URL rewriting with `.htaccess`

## Tech Stack

* **Backend:** PHP
* **Database:** MySQL / MariaDB
* **Frontend:** HTML, CSS, JavaScript
* **Web server:** Apache
* **Local development:** XAMPP
* **Production hosting:** InfinityFree

## How It Works

The application follows a simple URL-shortening workflow:

1. The user submits a long URL.
2. The application validates the URL.
3. A random short code or user-provided custom alias is generated.
4. The short code and original URL are stored in MySQL.
5. The application returns a short URL.
6. When the short URL is visited, Apache's rewrite rules route the request to `redirect.php`.
7. `redirect.php` looks up the short code in the database.
8. The click count is incremented.
9. The visitor is redirected to the original URL.

### Example

```text
Long URL
https://example.com/a/very/long/url

        ↓

Short URL
https://urlshortener.42web.io/abc12345
```

Custom aliases can also be used:

```text
https://urlshortener.42web.io/lake
```

## Database

The application uses a MySQL database to store shortened URLs and their associated information.

The `urls` table contains:

* `id` — unique record identifier
* `original_url` — destination URL
* `short_code` — generated or custom short identifier
* `clicks` — number of successful redirects
* `created_at` — creation timestamp
* `expires_at` — optional expiration timestamp
* `is_active` — indicates whether the short URL is active

The database structure is provided in `database.sql`.

## Installation

### Requirements

* PHP 7.4+
* MySQL or MariaDB
* Apache web server
* PHP PDO extension
* Apache `mod_rewrite`

### 1. Clone the repository

```bash
git clone https://github.com/itbhat/php-url-shortener.git
cd php-url-shortener
```

### 2. Create the database

Create a MySQL database and import:

```text
database.sql
```

### 3. Configure the database

Create a copy of `config.example.php` named:

```text
config.php
```

Update it with your database credentials:

```php
define('DB_HOST', 'your-database-host');
define('DB_NAME', 'your-database-name');
define('DB_USER', 'your-database-user');
define('DB_PASS', 'your-database-password');
define('DB_CHARSET', 'utf8mb4');
```

### 4. Configure Apache

Make sure Apache's `mod_rewrite` is enabled and that `.htaccess` overrides are permitted.

### 5. Run the application

Place the project in your Apache web root and open it through your local development server.

For example, with XAMPP:

```text
http://localhost/php-url-shortener/
```

## Project Structure

```text
php-url-shortener/
│
├── index.php
├── redirect.php
├── functions.php
├── database.php
├── config.example.php
├── database.sql
├── .htaccess
├── styles.css
└── README.md
```

## Security Considerations

* Database queries use PDO prepared statements.
* User-provided URLs and aliases are validated before being stored.
* Output is escaped before being rendered in HTML.
* Database credentials are kept outside the repository using `config.php`.
* `config.php` is excluded from Git using `.gitignore`.

## Future Improvements

The current version focuses on the core URL-shortening functionality.

Planned improvements include:

* Dashboard for managing shortened URLs
* Authentication and user accounts
* URL management and deletion
* More detailed click analytics
* Improved error pages
* Additional API functionality

## License

This project is available for portfolio and educational purposes.
