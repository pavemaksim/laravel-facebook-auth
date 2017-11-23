# Laravel Facebook Auth

This is a simple web app built with Laravel which allows you to login using your Facebook
account.

When a user logs in via Facebook we save his username and avatar in the database.
User can also logout properly.

If a user removes the Facebook app this web app receives a deauth callback from Facebook,
then marks that user with "is_active = false".

Additionally, a user long lived access token is saved in the database after his login.

## Requirements

PHP 7.0+
MySQL 5.6
Composer

## Deploy

- Clone the repo
- Go to root of the project and run `composer install`
- Set up your env config file: `cp .env.example .env`
- Edit `.env` file and use your MySQL database credentials
- Generate Laravel API_KEY: `php artisan key:generate`
- Configure your Developers Facebook account: get `client_id` and `client_secret`. Put these parameters in `.env`.
Example can be found in `.env.example`
- Fill the "Site URL" in "Developers Facebook" with your host info, e.g. "http://localhost:8000"
- Fill the "Deauthorize Callback URL" in "Developers Facebook" with your deauth URL, e.g. "http://localhost:8000/deauth"
- Fill the "Valid OAuth redirect URIs" with your host info, e.g. "http://localhost:8000"
- Set web server root to `public/index.php`. For dev environment you can just use `php artisan serve`

# Design approaches

- I keep Facebook Callback URL in services config as well because I want to keep it simple for this simple app for now.
Another approach with a "ConfigServiceProvider" can be considered for production: https://github.com/laravel/framework/issues/7671


