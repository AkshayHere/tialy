
## Initial DB Setup

```sql
/* To Create Database */
CREATE DATABASE `tialy`;

/** Create user and assign privilages */
mysql -u [rootuser] -p /** root user login */
CREATE USER 'tialyuser'@'localhost' IDENTIFIED BY 'acchx2k4sNHPK9quBth7aA==';
GRANT ALL PRIVILEGES ON * . * TO 'tialyuser'@'localhost';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'tialyuser'@'localhost';
```

# Tialy


> ### Example Laravel codebase containing real world examples (CRUD, auth, advanced patterns and more) that adheres to the [RealWorld](https://github.com/gothinkster/realworld-example-apps) spec and API.

This repo is functionality complete — PRs and issues welcome!

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Clone the repository

    git clone https://github.com/AkshayHere/tialy

Switch to the repo folder

    cd tialy

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:AkshayHere/tialy
    cd tialy
    composer install
    cp .env.example .env
    php artisan key:generate
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

The api can be accessed at [http://localhost:8000/api](http://localhost:8000/api).

## API Specification


More information regarding the project can be found here https://github.com/AkshayHere/tialy

----------

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/admin

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|
| Yes      	| Accept 	| application/json   	|
| Yes 	| Authorization    	| Bearer Token       	|

Refer the [api specification](#api-specification) for more info.

----------
 
# Authentication
 
This applications uses JSON Web Token (JWT) to handle authentication. The token is passed with each request using the `Authorization` header with `Token` scheme. The JWT authentication middleware handles the validation and authentication of the token. Please check the following sources to learn more about JWT.
 
- https://jwt.io/introduction/
- https://self-issued.info/docs/draft-ietf-oauth-json-web-token.html
