# HR Backend app

Private sourcecode repository for Bartech HR App backend and api

## Laravel notes

- To run on public port 8000 `php artisan serve --host 0.0.0.0`
- Creating Your Own PHP Helper Functions In Laravel https://tutsforweb.com/creating-helpers-laravel/
- Multiple DB Connections in Laravel https://fideloper.com/laravel-multiple-database-connections 
(Note to remove env('','') )
- Adding React to ui https://laravel.com/docs/7.x/frontend

### Laravel migrations

- To specify migrations table connection `php artisan migrate:install --database='mysql'`

### Laravel API auth using passport

- Adding API auth with passport https://laravel.com/docs/7.x/passport ( External react app auth )
- Passport migration Customization `php artisan vendor:publish --tag=passport-migrations`
- Note to add `connection('mysql')->` to migrations
- https://github.com/laravel/passport/issues/247 before running `php artisan passport:install` add config\passport.php 

```
<?php

return [
    'storage' => [
        'database' => [
            'connection' => 'mysql',
        ],
    ],
];
```

## API Documentation
