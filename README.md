## About

This Laravel application is developed by [Hasanta Sumudupriya](https://www.linkedin.com/in/hsumudupriya) as a technical task for Heartpace. The objective of the application is to provide a basic e-signature API that allows users to e-sign documents.

## System requirements

This application utilizes Docker to run in development environments. Download and install Docker using this [link](https://docs.docker.com/get-started/get-docker/) to run this application easily.

## Run the application.

Open bash/terminal/command line tool and run the commands below to start the application.

1. `git clone git@github.com:hsumudupriya/heartpace-basic-e-signature-api.git`
1. `cd heartpace-basic-e-signature-api`
1. `docker-compose up`
1. Open your browser and visit [http://localhost/docs](http://localhost/docs) to view the API documentation.

The `docker-compose up` command will handle everything for you and start the application. It executes the steps below to start the application.

1. If you are running the application for the first time, the `docker-compose up` command:
    1. Builds a Docker volume for the MySQL service,
    1. Builds 2 Docker images. One for the MySQL service. The other for the application.
1. Creates 2 Docker containers from the above images.
1. Executes the commands below to properly setup the application and then starts it.

![start-container](/start-container.jpg "start-container")

Since it is taking care of things for you, it might take some time to start the application.

## Run the tests

The `docker-compose up` command runs the tests before starting the application. Run `docker-compose exec laravel php artisan test` command in another bash/terminal/command line tool to test the application yourself.

The below tests are implemented in the application.

![tests](/test-results.jpg "tests")

## How to use the API

### Demo video

Watch my [demo video](https://youtu.be/NMwKWW-5wXs) on how to use this application. Or else, you can follow the steps below.

### Register a new user

1. Visit [http://localhost/docs](http://localhost/docs) to view the API documentation.
1. Go to Authentication > Register from the left-hand menu.
1. Click on the "Try it out" button.
1. In Body Parameters, enter values for name, email, password, and password confirmation.
1. Click on the "Send Request" button. You should see the newly created user on the right-hand side.

### Log in and create an access token

1. Go to Authentication > Login from the left-hand menu.
1. Click on the "Try it out" button.
1. In Body Parameters, enter the email and the password.
1. Click on the "Send Request" button. You should see your access token on the right-hand side.
1. Select the access token and copy it.

### Upload a document

1. Go to Documents > Upload a document from the left-hand menu.
1. Click on the "Try it out" button.
1. Replace the {YOUR_ACCESS_TOKEN} string in the Authorization input with the access token.
1. In Body Parameters, click on the "Choose file" button and select a PDF file.
1. Click on the "Send Request" button. You should see the data about the uploaded file on the right-hand side.
1. Select the id and copy it.

### Create a signature request.

1. Create another new user to request a signature.
1. Go to Signature requests > Create a request from the left-hand menu.
1. Click on the "Try it out" button.
1. Replace the {YOUR_ACCESS_TOKEN} string in the Authorization input with the access token. If the documentation has automatically replaced the string, leave it as it is.
1. Fill the body parameters as below.
    1. document_id - ID of your document.
    1. requested_from - Email address of the new user.
    1. signature_positions - Position of the signature. Expand the parameter and enter 1 as the page, 50 as X and Y.
        1. Usually the position of the signature will automatically be sent by the frontend application when consuming this API.
    1. deadline - A deadline in the format 2024-10-11T10:30:00+05:30.
1. Click on the "Send Request" button. You should see the data about the signature request on the right-hand side.
1. Select the id and copy it.

### Sign a document.

1. Login as the 2nd user and create an access token.
1. Go to Sign a document > Sign a document from the left-hand menu.
1. Click on the "Try it out" button.
1. Replace the {YOUR_ACCESS_TOKEN} string in the Authorization input with the access token of the 2nd user. If you see the access token of the 1st user replace it.
1. Fill the body parameters as below.
    1. request_id - ID of the signature request.
    1. signature - Select a PNG, JPG, or JPEG image of your signature.
1. Click on the "Send Request" button. You should see the data about the signed document on the right-hand side.
1. Select the id and copy it.

### Download a signed document.

1. Go to Documents > Download a document from the left-hand menu.
1. Click on the "Try it out" button.
1. Replace the {YOUR_ACCESS_TOKEN} string in the Authorization input with the access token of the 2nd user. If the documentation has automatically replaced the string, leave it as it is.
1. Enter the ID of the document in the URL Parameters.
1. Click on the "Send Request" button.
1. Save the document to your computer.

## Additional info

ER diagram of the application

![erd](/erd.jpg "erd")
