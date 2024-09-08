## About

This repository contains a test task completed by [Hasanta Sumudupriya](https://www.linkedin.com/in/hsumudupriya) for Heartpace. The objective of the task is to create a basic E-Signature API using Laravel that allows users to e-sign documents.

## System Requirements

Below programs and their extensions are required to run this application.

1. PHP 8.2 or higher and these PHP extensions.
    1. ext-ctype
    1. ext-dom
    1. ext-fileinfo
    1. ext-filter
    1. ext-hash
    1. ext-iconv
    1. ext-json
    1. ext-libxml
    1. ext-mbstring
    1. ext-openssl
    1. ext-pcre
    1. ext-pdo
    1. ext-phar
    1. ext-session
    1. ext-tokenizer
    1. ext-xml
    1. ext-xmlwriter
    1. ext-zli
1. Composer
1. MySQL

## Run the application.

Follow the steps below to run the application.

1. Clone this repository to your local environment.
1. Open bash/terminal/command line tool and run the commands below.
    1. `cd ./into-the-cloned-directory`
    1. `composer install`
    1. `cp .env.example .env`
    1. Edit the .env file using `vim .env` and update the below environment variables.
        1. `APP_TIMEZONE`
        1. `APP_URL`
        1. `DB_HOST`
        1. `DB_PORT`
        1. `DB_DATABASE`
        1. `DB_USERNAME`
        1. `DB_PASSWORD`
    1. `php artisan key:generate`
    1. `php artisan migrate`
    1. `cd storage/app`
    1. `openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout certificate.crt -out certificate.crt`
    1. `php artisan serve`
1. Visit [http://localhost:8000/docs](http://localhost:8000/docs) to view the API documentation.

## Run the tests

Open bash/terminal/command line tool and run the commands below to test the application.

1. `cp .env.example .env.testing`
1. Edit the .env file using `vim .env` and update the below environment variables.
    1. `APP_ENV` (set to `testing`)
    1. `APP_TIMEZONE`
    1. `APP_URL`
    1. `DB_HOST`
    1. `DB_PORT`
    1. `DB_DATABASE`
    1. `DB_USERNAME`
    1. `DB_PASSWORD`
1. `php artisan key:generate --env=testing`
1. `php artisan migrate --env=testing`
1. `php artisan test`

Below tests are implemented in the application.
![tests](/test-results.jpg "tests")
