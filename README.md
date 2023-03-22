# ARGZT_ASSESSMENT
## Installation
- Clone the repository: git clone   https://github.com/hadsle/ARZGT_Assessment.git
- Install dependencies: composer install
- Copy the .env.example file to .env: cp .env.example .env
- Generate an application key: php artisan key:generate
- Set up a database and fill in the database credentials in the .env file
- Set up a SMTP mail and fill in the mail in the .env file to verify sign-up
- Migrate the database: php artisan migrate

## Features

- User authentication and authorization
- User management system with admin access control
- User activity logging
- Responsive design

## Installation
Database Credentiels
```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tasksolution
DB_USERNAME=root
DB_PASSWORD=your password

```

MAILHOG Credentiels

```sh
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```
Google reCAPTCHA key (this key used to test only ) 
```sh
GOOGLE_CAPTCHA_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
GOOGLE_CAPTCHA_SECRET=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
```
## Requirements
- PHP >= 7.3
- Composer
- MySQL 
- Google reCAPTCHA site key and secret key

## Usage
- Start the server: php artisan serve
- Open the application in a web browser: http://localhost:8000

