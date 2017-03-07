# Rest API with Lumen 5.4

A RESTful API boilerplate for Lumen micro-framework. Features included:

- OAuth2 Authentication
- Validation
- Repository Pattern
- API Response with [Fractal](http://fractal.thephpleague.com/)
- Pagination
- Seeding Database With Model Factory
- [CORS](https://github.com/barryvdh/laravel-cors) Support

## Getting Started

First, clone the repo
```
git clone git@github.com:hasib32/rest-api-with-lumen.git
```

#### Laravel Homestead
You can use Laravel Homestead globally or per project for local development. Follow the [Installation Guide.](https://laravel.com/docs/5.4/homestead#installation-and-setup)

#### Install dependencies

```
cd rest-api-with-lumen
composer install
```
#### Configure the Environment
```
# Create .env file 
cat .env.example > .env
```
If you want you can edit database name, database username and database password.

#### Migrations and Seed the database with fake data
```
php artisan migrate --seed

```
## Contributing
Contributions, questions and comments are all welcome and encouraged. For code contributions submit a pull request.


## License

 [MIT license](http://opensource.org/licenses/MIT)
