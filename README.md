# Fluent Ukrainian Project
## Dependecies:
- PHP
- Apache HTTP server
- Composer

Composer dependencies are listed in composer.json and composer.lock files.

## Configuration
Create config.php and google.config.php files and configure your credentials there.

## Templates:

`config.php`
```
<?php
define('__HOSTNAME__', "your_hostname_here");
define('__USERNAME__', "your_username_here");
define('__PASSWORD__', "your_password_here");
define('__DATABASE__', "your_main_db_name_here");
?>
```

`google.config.php`
```
define('CLIENT_ID_LOGIN', "your_google_API_login_id");
define('CLIENT_SECRET_LOGIN',"your_google_API_login_secret");
define('CLIENT_ID_SIGNUP', 'your_google_API_signup_id');
define('CLIENT_SECRET_SIGNUP', 'your_google_API_signup_secret');
define('CACERT_PATH', "your_cert_path");
```