
# Tialy

> ### A URL Shortner based on [laravel](https://laravel.com/)
> Motivated by [Tech in Asia](https://www.techinasia.com/)

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


## Initial DB Setup

Make sure that [MySQL](https://www.mysql.com/) is installed in your PC.
From the [MySQL Downloads Page](https://dev.mysql.com/downloads/), MySQL Workbench & MySQL Server is installed.

Execute the below commands in

```sql
/* Create Database */
CREATE DATABASE `tialy`;

/** Create user and assign privilages */
CREATE USER 'tialyuser'@'localhost' IDENTIFIED BY 'acchx2k4sNHPK9quBth7aA==';
GRANT ALL PRIVILEGES ON * . * TO 'tialyuser'@'localhost';
FLUSH PRIVILEGES;

/** Show User Privillages */
SHOW GRANTS FOR 'tialyuser'@'localhost';
```

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

----------

## Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|
| Yes      	| Accept 	| application/json   	|
| Yes 	| Authorization    	| Bearer Token       	|

Refer the [api specification](#api-specification) for more info.

----------

## API Specification

The various routes are protected by bearer token. So to start using the various endpoints we need to generate the token using your details.

Most of the errors responses are designed using the below structure 

```JSON
{
  "timestamp": "2020-01-01T18:10:18+00:00", /* current time stamp*/
  "success": "error",
  "warnings": [],
  "errors": [
    /* array of errors */
    {
      "code": "0", /* error code */
      "message": "{error_message}" /* error message returned */
    }
  ]
}
```

### Register User

`POST /api/register`

Inorder, to start using the various endpoints, We need to authenticate with the system and generate a token using the `name`, `email` and `password`.

A sample request is attached below

```JSON
{
	"name": "{name}", /* full name preferred */
	"email": "{email_address}", /* email address to register */
	"password": "{password}" /* basic password for future reference */
}
```

A sample response is attached below

```JSON
{
  "token_type": "Bearer",
  "expires_in": 31536000,
  "access_token": "{access token}",/* Token to access the end points */
  "refresh_token": "{refresh token}" /* U can ignore this */
}
```

All the below routes are protected and requires the token generated from the previous url to access them

### Generate Short URL

`POST /admin/urls`

You can pass two parameters - `url` which is mandatory and `customSlug` which is optional.

`customSlug` lets you create a short url whose end string can be customized.

A sample request is attached below

```JSON
{
	"url": "{url_to_shorten}", /* URL you wishes to shorten */
	"customSlug" : "" /* optional | any random string whose length is in the range of 8-20 characters */
}
```

A sample response is attached below

```JSON
{
  "timestamp": "2020-01-01T18:10:18+00:00", /* current time stamp*/
  "success": "ok", /* 'ok' if sucessful, 'error' if not sucessful */
  "warnings": [],
  "errors": [],
  "data": {
    "short_url": "{short_url}" /* short url generated */
  }
}
```

### List all Short URLs

`GET /admin/urls`

Displays all the short urls saved.
Make sure that Bearer token is used when trying to access this url

A sample response is attached below

```JSON
{
  "timestamp": "2020-01-01T18:10:18+00:00", /* current time stamp*/
  "success": "ok", /* 'ok' if sucessful, 'error' if not sucessful */
  "warnings": [],
  "errors": [],
  "data": [
      /* List of short url infos*/
      {
      "slug": "{slug}",/* Slug code for Short URL */
      "redirect_url": "{redirect_url}", /* Redirect URL */
      "creator_id": "{user_email}" /* user who created this*/
    },
    ....
  ]
}
```

### List Short URL Details

`GET /admin/urls/{slug}`

Displays the details about a short url using their slug
Make sure that Bearer token is used when trying to access this url

A sample response is attached below

```JSON
{
  "timestamp": "2020-01-01T18:10:18+00:00", /* current time stamp*/
  "success": "ok", /* 'ok' if sucessful, 'error' if not sucessful */
  "warnings": [],
  "errors": [],
  "data": {
      "slug": "{slug}",/* Slug code for Short URL */
      "redirect_url": "{redirect_url}", /* Redirect URL */
      "creator_id": "{user_email}" /* user who created this*/
    }
}
```

### Delete Short URL Details

`DELETE /admin/urls/{slug}`

Delete the short url details using slug
Make sure that Bearer token is used when trying to access this url

A sample response is attached below

```JSON
{
  "timestamp": "2020-01-01T18:10:18+00:00", /* current time stamp*/
  "success": "ok", /* 'ok' if sucessful, 'error' if not sucessful */
  "warnings": [],
  "errors": []
}
```

### Update the Short URL Details

`PUT /admin/urls/{slug}`

Update the short url details using slug
Make sure that Bearer token is used when trying to access this url and set header as `Content-Type : application/x-www-form-urlencoded`.

A sample response is attached below

```JSON
{
  "timestamp": "2021-01-03T05:53:12+00:00",
  "success": "ok",
  "warnings": [],
  "errors": [],
  "data": {
    "slug": "{slug}", /* this will be same as the form params */
    "redirect_url": "{redirect_url}", /* new redirect url */
    "creator_id": "{user_email}" /* user who created the url */
  }
}
```