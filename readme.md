
# REST API with Lumen 5.4 [![Build Status](https://travis-ci.org/hasib32/rest-api-with-lumen.svg?branch=master)](https://travis-ci.org/hasib32/rest-api-with-lumen)

A RESTful API boilerplate for Lumen micro-framework. Features included:

- Users Resource
- OAuth2 Authentication using Laravel Passport
- Scope based Authorization
- Validation
- [Repository Pattern](https://msdn.microsoft.com/en-us/library/ff649690.aspx)
- API Response with [Fractal](http://fractal.thephpleague.com/)
- Pagination
- Seeding Database With Model Factory
- Event Handling
- Sending Mail using Mailable class
- [CORS](https://github.com/barryvdh/laravel-cors) Support
- [Rate Limit API Requests](https://mattstauffer.co/blog/api-rate-limiting-in-laravel-5-2)
- Endpoint Tests and Unit Tests
- Build Process with [Travis CI](https://travis-ci.org/)

## Getting Started
First, clone the repo:
```bash
$ git clone git@github.com:hasib32/rest-api-with-lumen.git
```

#### Laravel Homestead
You can use Laravel Homestead globally or per project for local development. Follow the [Installation Guide](https://laravel.com/docs/5.4/homestead#installation-and-setup).

#### Install dependencies
```
$ cd rest-api-with-lumen
$ composer install
```

#### Configure the Environment
Create `.env` file:
```
$ cat .env.example > .env
```
If you want you can edit database name, database username and database password.

#### Migrations and Seed the database with fake data
First, we need connect to the database. For homestead user, login using default homestead username and password:
```bash
$ mysql -uhomestead -psecret
```

Then create a database:
```bash
mysql> CREATE DATABASE restapi;
```

And also create test database:
```bash
mysql> CREATE DATABASE restapi_test;
```

Run the Artisan migrate command with seed:
```bash
$ php artisan migrate --seed
```

Create "personal access" and "password grant" clients which will be used to generate access tokens:
```bash
$ php artisan passport:install
```

You can find those clients in ```oauth_clients``` table.

### API Routes
| HTTP Method	| Path | Action | Desciption  |
| ----- | ----- | ----- | ------------- |
| GET      | /users | index | Get all users
| POST     | /users | store | Create an user
| GET      | /users/{user_id} | show |  Fetch an user by id
| PUT      | /users/{user_id} | update | Update an user by id
| DELETE      | /users/{user_id} | destroy | Delete an user by id

Note: ```users/me``` is a special route for getting current authenticated user.

### OAuth2 Routes
Visit [dusterio/lumen-passport](https://github.com/dusterio/lumen-passport/blob/master/README.md#installed-routes) to see all the available ```OAuth2``` routes.

### Creating access_token
Since Laravel Passport doesn't restrict any user creating any valid scope. I had to create a route and controller to restrict user creating access token only with permitted scopes. For creating access_token we have to use the ```accessToken``` route. Here is an example of creating access_token for grant_type password with [Postman.](https://www.getpostman.com/)

http://stackoverflow.com/questions/39436509/laravel-passport-scopes

![access_token creation](/public/images/accessTokenCreation.png?raw=true "access_token creation example")

## Creating a New Resource
Creating a new resource is very easy and straight-forward. Follow these simple steps to create a new resource.

### Step 1: Create Route
Create a new route name ```messages```. Open the ```routes/web.php``` file and add the following code:

```php
$app->post('messages', 'MessageController@store');
$app->get('messages', 'MessageController@index');
$app->get('messages/{id}', 'MessageController@show');
$app->put('messages/{id}', 'MessageController@update');
$app->delete('messages/{id}', 'MessageController@destroy');
```

For more info please visit Lumen [Routing](https://lumen.laravel.com/docs/5.4/routing) page.

### Step 2: Create Model and Migration for the Table
Create ```Message``` Model inside ```App/Models``` directory and create migration using Lumen Artisan command.

**Message Model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'userId',
        'subject',
        'message',
    ];
}
```

Visit Laravel [Eloquent](https://laravel.com/docs/5.4/eloquent) Page for more info about Model.

**Create migration for messages table**

```bash
php artisan make:migration create_messages_table --create=messages
```
**Migration file**

```php
class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid', 36)->unique();
            $table->integer('userId')->unsigned();
            $table->string('subject')->nullable();
            $table->longText('message');
            $table->timestamps();

            $table->foreign('userId')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
```

For more info visit Laravel [Migration](https://laravel.com/docs/5.4/migrations) page.

### Step 3: Create Repository
Create ```MessageRepository``` and implementation of the repository name ```EloquentMessageRepository```.

**MessageRepository**

```php
<?php

namespace App\Repositories\Contracts;

interface MessageRepository extends BaseRepository
{
}
```

**EloquentMessageRepository**

```php
<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\Contracts\MessageRepository;

class EloquentMessageRepository extends AbstractEloquentRepository implements MessageRepository
{
    /**
     * Model name.
     *
     * @var string
     */
    protected $modelName = Message::class;
}
```

Next, update ```RepositoriesServiceProvider``` to bind the implementation:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\Contracts\MessageRepository;
use App\Repositories\EloquentMessageRepository;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, function () {
            return new EloquentUserRepository(new User());
        });
        $this->app->bind(MessageRepository::class, function () {
            return new EloquentMessageRepository(new Message());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            UserRepository::class,
            MessageRepository::class,
        ];
    }
}
```

Visit Lumen documentation for more info about [Service Provider](https://lumen.laravel.com/docs/5.4/providers).

### Step 4: Create Fractal Transformer
Fractal provides a presentation and transformation layer for complex data output, the like found in RESTful APIs, and works really well with JSON. Think of this as a view layer for your JSON/YAML/etc.

Create a new Transformer name ```MessageTransformer``` inside ```app/Transformers``` direcotry:

```php
<?php

namespace App\Transformers;

use App\Models\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {
        return [
            'id'        => $message->uid,
            'userId'    => $message->userId,
            'subject'   => $message->subject,
            'message'   => $message->message,
            'createdAt' => (string) $message->created_at,
            'updatedAt' => (string) $message->updated_at,
        ];
    }
}
```
Visit [Fractal](http://fractal.thephpleague.com/) official page for more information.

### Step 5: Create Policy
For authorization we need to create policy that way basic user can't show or edit other user messages.

**MessagePolicy**

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;

class MessagePolicy
{
    /**
     * Intercept checks.
     *
     * @param User $currentUser
     * @return bool
     */
    public function before(User $currentUser)
    {
        if ($currentUser->tokenCan('admin')) {
            return true;
        }
    }

    /**
     * Determine if a given user has permission to show.
     *
     * @param User $currentUser
     * @param Message $message
     * @return bool
     */
    public function show(User $currentUser, Message $message)
    {
        return $currentUser->id === $message->userId;
    }

    /**
     * Determine if a given user can update.
     *
     * @param User $currentUser
     * @param Message $message
     * @return bool
     */
    public function update(User $currentUser, Message $message)
    {
        return $currentUser->id === $message->userId;
    }

    /**
     * Determine if a given user can delete.
     *
     * @param User $currentUser
     * @param Message $message
     * @return bool
     */
    public function destroy(User $currentUser, Message $message)
    {
        return $currentUser->id === $message->userId;
    }
}
```
Next, update ```AuthServiceProvider``` to use the policy:
```
Gate::policy(Message::class, MessagePolicy::class);
```
Visit Lumen [Authorization Page](https://lumen.laravel.com/docs/5.4/authorization) for more info about Policy.

### Last Step: Create Controller
 
Finally, let's create the ```MessageController```. Here we're using **MessageRepository, MessageTransformer and MessagePolicy**.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Repositories\Contracts\MessageRepository;
use Illuminate\Http\Request;
use App\Transformers\MessageTransformer;

class MessageController extends Controller
{
    /**
     * Instance of MessageRepository.
     *
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * Instanceof MessageTransformer.
     *
     * @var MessageTransformer
     */
    private $messageTransformer;

    /**
     * Constructor.
     *
     * @param MessageRepository $messageRepository
     * @param MessageTransformer $messageTransformer
     */
    public function __construct(MessageRepository $messageRepository, MessageTransformer $messageTransformer)
    {
        $this->messageRepository = $messageRepository;
        $this->messageTransformer = $messageTransformer;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $messages = $this->messageRepository->findBy($request->all());

        return $this->respondWithCollection($messages, $this->messageTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function show($id)
    {
        $message = $this->messageRepository->findOne($id);

        if (!$message instanceof Message) {
            return $this->sendNotFoundResponse("The message with id {$id} doesn't exist");
        }

        // Authorization
        $this->authorize('show', $message);

        return $this->respondWithItem($message, $this->messageTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function store(Request $request)
    {
        // Validation
        $validatorResponse = $this->validateRequest($request, $this->storeRequestValidationRules($request));

        // Send failed response if validation fails
        if ($validatorResponse !== true) {
            return $this->sendInvalidFieldResponse($validatorResponse);
        }

        $message = $this->messageRepository->save($request->all());

        if (!$message instanceof Message) {
            return $this->sendCustomResponse(500, 'Error occurred on creating Message');
        }

        return $this->setStatusCode(201)->respondWithItem($message, $this->messageTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validatorResponse = $this->validateRequest($request, $this->updateRequestValidationRules($request));

        // Send failed response if validation fails
        if ($validatorResponse !== true) {
            return $this->sendInvalidFieldResponse($validatorResponse);
        }

        $message = $this->messageRepository->findOne($id);

        if (!$message instanceof Message) {
            return $this->sendNotFoundResponse("The message with id {$id} doesn't exist");
        }

        // Authorization
        $this->authorize('update', $message);


        $message = $this->messageRepository->update($message, $request->all());

        return $this->respondWithItem($message, $this->messageTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function destroy($id)
    {
        $message = $this->messageRepository->findOne($id);

        if (!$message instanceof Message) {
            return $this->sendNotFoundResponse("The message with id {$id} doesn't exist");
        }

        // Authorization
        $this->authorize('destroy', $message);

        $this->messageRepository->delete($message);

        return response()->json(null, 204);
    }

    /**
     * Store Request Validation Rules
     *
     * @param Request $request
     * @return array
     */
    private function storeRequestValidationRules(Request $request)
    {
       return [
           'userId'     => 'required|exists:users,id',
           'subject'    => 'required',
           'message'    => 'required',
        ];
    }

    /**
     * Update Request validation Rules
     *
     * @param Request $request
     * @return array
     */
    private function updateRequestValidationRules(Request $request)
    {
        return [
            'subject'    => '',
            'message'    => '',
        ];
    }
}
```

Visit Lumen [Controller](https://lumen.laravel.com/docs/5.4/controllers) page for more info about Controller.

## Tutorial
To see the step-by-step tutorial how I created this boilerplate please visit our blog [devnootes.net](https://devnotes.net/rest-api-development-with-lumen-part-one/).

## Contributing
Contributions, questions and comments are all welcome and encouraged. For code contributions submit a pull request.

## Credits
[Taylor Otwell](https://github.com/taylorotwell), [Shahriar Mahmood](https://github.com/shahriar1), [Fractal](http://fractal.thephpleague.com/), [Phil Sturgeon](https://github.com/philsturgeon)
## License

 [MIT license](http://opensource.org/licenses/MIT)
