## About

This is a Laravel application developed by [Hasanta Sumudupriya](https://www.linkedin.com/in/hsumudupriya) as a technical task for Heartpace. The objective of the application is to provide a basic e-signature API that allows users to e-sign documents.

## System requirements

This application utilizes Docker and Laravel Sail to run in development environments. Download and install Docker using this [link](https://docs.docker.com/get-started/get-docker/) to run this application easily.

Additionally you need below applications in your system to setup this project.

1. PHP 8.2 or higher with below extensions.
    1. ctype
    1. dom
    1. fileinfo
    1. filter
    1. hash
    1. iconv
    1. json
    1. libxml
    1. mbstring
    1. openssl
    1. pcre
    1. pdo
    1. phar
    1. session
    1. tokenizer
    1. xml
    1. xmlwriter
    1. zip
    1. zli
1. Composer

## Run the application.

Open bash/terminal/command line tool and run the commands below to test the application.

1. `git clone git@github.com:hsumudupriya/heartpace-basic-e-signature-api.git`
1. `cd heartpace-basic-e-signature-api`
1. `cp .env.example .env`
1. `composer install`
1. `./vendor/bin/sail up -d`
1. `./vendor/bin/sail artisan key:generate`
1. `./vendor/bin/sail artisan migrate`

Visit [http://localhost/docs](http://localhost/docs) to view the API documentation.

Run the commands below to create a certificate to certify the signatures.

1. `./vendor/bin/sail shell`
1. `cd storage/app`
1. `openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout certificate.crt -out certificate.crt`

## Run the tests

Open bash/terminal/command line tool and run the commands below to test the application.

1. `cp .env.example .env.testing`
1. `./vendor/bin/sail artisan key:generate --env=testing`
1. `./vendor/bin/sail artisan migrate --env=testing`
1. `./vendor/bin/sail artisan test`

Below tests are implemented in the application.

![tests](/test-results.jpg "tests")

## Additional info

ER diagram of the application

![erd](/erd.jpg "erd")
