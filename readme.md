# Rest API with Lumen 5.4

A RESTful API boilerplate for Lumen micro-framework. Features included:

- users Resource
- OAuth2 Authentication using Laravel Passport
- Scope based Authorization
- Validation
- Repository Pattern
- API Response with [Fractal](http://fractal.thephpleague.com/)
- Pagination
- Seeding Database With Model Factory
- Event Handling
- Sending Mail using Mailable class
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
# Login using default homestead username and password
mysql -uhomestead -psecret
mysql> CREATE DATABASE restapi;

# Run the Artisan migrate command with seed
php artisan migrate --seed

# Create "personal access" and "password grant" clients which will be used to generate access tokens. 
php artisan passport:install
# You can find those clients in "oauth_client" table
```

### API Routes

| HTTP Method	| Path | Action | Desciption  |
| ----- | ----- | ----- | ------------- |
| GET      | /users | index | Get all users
| POST     | /user | store | Create an user
| GET      | /users/{user_id} | show |  Fetch an user by id
| PUT      | /users/{user_id} | update | Update an user by id
| DELETE      | /users/{user_id} | destroy | Delete an user by id

Note: ```users/me``` route is for getting current authenticated user.


### Oauth2 Routes
Visit [dusterio/lumen-passport](https://github.com/dusterio/lumen-passport/blob/master/README.md#installed-routes) to see all the available ```Oauth2``` routes.

### Creating access_token
Since Laravel Passport doesn't restrict any user creating any valid scope. I had to create a route and controller to restrict user creating access token only with permitted scopes. For creating access_token we have to use the ```accessToken``` route. Here is an example of creating access_token for grant_type password with [Postman.](https://www.getpostman.com/)

http://stackoverflow.com/questions/39436509/laravel-passport-scopes

![access_token creation](/public/images/accessTokenCreation.png?raw=true "access_token creation example")

## Creating a New Resource
creating a new resource is very easy and straight-forward.

## Tutorial
To see the step-by-step tutorial how I created this boilerplate please visit our blog [devnootes.net](https://devnotes.net/rest-api-development-with-lumen-part-one/)
## Contributing
Contributions, questions and comments are all welcome and encouraged. For code contributions submit a pull request.


## License

 [MIT license](http://opensource.org/licenses/MIT)
