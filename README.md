# sci-reflect

Tool to help students reflect on skills gained during their degree.

## Requirements

Per [Laravel 5.4](https://laravel.com/docs/5.4): PHP >= 5.6.4 (written with 7.0) + various extensions, [Composer](https://getcomposer.org/), [nodeJS](https://nodejs.org/en/).

## Installation

* git clone https://github.com/lboro-science-it/sci-reflect.git
* mv .env.example .env
* update DB credentials in .env
* composer install
* php artisan key:generate
* php artisan migrate

## Usage

Manually add consumerKey + sharedSecret to DB table lti2_consumer, set enabled to 1.
Default launch URL: https://path/to/public/launch.
Add custom formats for activity to app/Reflect/Formats\FormatName as Activity.php and Page.php, extending App\Reflect\Formats\BaseFormat.