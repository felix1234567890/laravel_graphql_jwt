# Instructions

1. Rename .env.example to .env and fill in db options. Make sure DB_HOST=laraapp-db
2. Run `docker-compose build app`
3. Run `docker-compose up -d`
4. Run `docker-compose exec app composer install`
5. Run `docker-compose exec app php artisan key:generate`
6. Run `docker-compose exec app php artisan migrate --seed`
7. Open GraphQL playground on `http://localhost:8080/graphiql` and run queries
8. Don't forget to run register and login mutations to obtain access token which should be pasted inside Authorization header
