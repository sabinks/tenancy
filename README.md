## About Project

Making multi tenancy project with multi database with Passport Authentication with simple proxy server.

## Stack

-   php8.3
-   laravel 11.31
-   passport 12.3
-   stancl/tenancy: 3.8

## Env file

Check .env.example file for reference

### Step

-   copy tenancy.conf, tenancy1.conf, tenancy2.conf to /etc/nginx/sites-available and create symlink /etc/nginx/sites-enabled
-   php artisan migrate
-   php artisan tenants:migrate
-   php artisan passport:keys
-   php artisan tenants:mgirate
