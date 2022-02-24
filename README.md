Data Migrations for Laravel
===========================
This extension allows you to separate data migrations from structure migrations.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

With Composer installed, you can then install the extension using the following commands:

```bash
composer require coderan/laravel-data-migrations
```

or add 

```json
...
    "require": {
        "coderan/laravel-data-migrations": "^1.0"
    }
```

to the ```require``` section of your `composer.json` file.

## Configuration

1. Register the ServiceProvider in your config/app.php service provider list. This step can be skipped in Laravel 5.5+

config/app.php
```php
return [
    //other stuff
    'providers' => [
        \Coderan\DataMigrations\DataMigrationsServiceProvider::class,
    ];
];
```

2. Publish the new assets.
```shell
php artisan vendor:publish --tag=data-migrations
```
This will create the default migrations directory and the config/data-migrations.php file.

## Usage

By default, the table used to store the data migrations is "migrations_data" table. You 
can change the table on the config/data-migrations.php file.

The data migrations will be stored in the migrations_data folder of the database path if no 
path is specified in the command execution.

The available commands of the package are:

*Create migration command*
```shell
php artisan make:data-migration [name] [--path=]
```
The first time you use it the data migrations table will be created.

*Run migration command*
```shell
php artisan migrate-data [--path=]
```

*Rollback migration command*
```shell
php artisan migrate-data:rollback [--path=]
```

The behavior of the migrations is the same as the regular migrations.

## License 
Copyright &copy; 2021 José Lorente Martín <jose.lorente.martin@gmail.com>, Eran Machiels <dev@eranmachiels.nl>.

Licensed under the MIT license. See LICENSE.txt for details.
