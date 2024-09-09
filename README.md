## About

This is a Laravel application developed by [Hasanta Sumudupriya](https://www.linkedin.com/in/hsumudupriya) as a technical task for Heartpace. The objective of the application is to provide a basic e-signature API that allows users to e-sign documents.

## System requirements

This application utilizes Docker and Laravel Sail to run in development environments. Download and install Docker using this [link](https://docs.docker.com/get-started/get-docker/) to run this application easily.

## Run the application.

Open bash/terminal/command line tool and run the commands below to test the application.

1. `git clone git@github.com:hsumudupriya/heartpace-basic-e-signature-api.git`
1. `cd heartpace-basic-e-signature-api`
1. `cp .env.example .env`
1. `cp .env.example .env.testing`
1. `docker-compose up`

Visit [http://localhost/docs](http://localhost/docs) to view the API documentation.

## Run the tests

The `docker-compose up` command runs the tests before starting the application. Run `docker-compose exec laravel php artisan test` command in another bash/terminal/command line tool to test the application by yourself.

Below tests are implemented in the application.

![tests](/test-results.jpg "tests")

## Additional info

ER diagram of the application

![erd](/erd.jpg "erd")
