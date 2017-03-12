# Rest API with Lumen 5.4

A RESTful API boilerplate for Lumen micro-framework. Features included:

- users Resource
- OAuth2 Authentication
- Scope based Authorization
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

First, we need to create a database. For homestead user,
```
mysql -uhomestead -psecret
mysql> CREATE DATABASE restapi;

# Finally, run the Artisan migrate command
php artisan migrate --seed
```
### API Routes

| HTTP Method	| Path | Action | Desciption  |
| ----- | ----- | ----- | ------------- |
| GET      | /users | index | Get all users
| POST     | /user | store | Create an user
| GET      | /users/{user_id} | show |  Fetch an user by id
| PUT      | /users/{user_id} | update | Update an user by id
| DELETE      | /users/{user_id} | destroy | Delete an user by id


### Oauth2 Routes
Visit [dusterio/lumen-passport](https://github.com/dusterio/lumen-passport/blob/master/README.md#installed-routes) to see all the available ```Oauth2``` routes.

You can test the API using [Postman.](https://www.getpostman.com/) Here is an example of creating access_token.

![access_token creation](/public/images/accessTokenCreation.png?raw=true "access_token creation example")

## Tutorial
To see the step-by-step tutorial how I created this boilerplate please visit our blog [devnootes.net](https://devnotes.net/rest-api-development-with-lumen-part-one/)
## Contributing
Contributions, questions and comments are all welcome and encouraged. For code contributions submit a pull request.


## License

 [MIT license](http://opensource.org/licenses/MIT)
