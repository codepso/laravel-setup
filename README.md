# Codepso / Laravel / Catalog

## Requirements

* php >= 8.0.0
* laravel >= 8.12.0
* composer >= 2.0.7

## Installation
```bash
composer require codepso/laravel
php artisan vendor:publish --tag=codepso-config (optional)
composer update codepso/laravel (optional)
````

### Docker
```bash
PGSLQ

docker run --rm -it -v $(pwd):/var/www codepso/php:8.0-cli-pgsql composer require codepso/laravel
docker run --rm -it -v $(pwd):/var/www codepso/php:8.0-cli-pgsql composer update codepso/laravel

MYSQL

docker run --rm -it -v $(pwd):/var/www codepso/php:8.1-cli-mysql composer require codepso/laravel
docker run --rm -it -v $(pwd):/var/www codepso/php:8.1-cli-mysql composer update codepso/laravel
```

## Configuration

.env
```bash
CATALOG_SIZE=5
````

## Testing
```bash
laravel new testing
composer remove codepso/laravel
php artisan serve
```

```bash
"repositories": [
    {
        "type": "path",
        "url": "./packages/codepso/laravel"
    }
]
```
```bash
composer update
composer require codepso/laravel
composer remove codepso/laravel (remove)
```

# References
- https://cerwyn.medium.com/creating-laravel-package-from-scratch-4582607639cf
- https://www.twilio.com/blog/build-laravel-php-package-seeds-database-fake-data
- https://dev.to/devingray/how-to-create-a-highly-configurable-laravel-package-4pj0
