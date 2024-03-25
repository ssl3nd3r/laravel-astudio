# ASTUDIO LARAVEL ASSESMENT

Databasee driver used is SQLite so there is no need to run a MySQL server

Instructions to run the app:

composer install

php artisan key:generate

php artisan migrate:fresh --seed



As mentioned in the documents, all routes are wrapped under the auth middleware

You can retrieve an auth token by signing in using the auth routes

All users seeded have the same password `password`