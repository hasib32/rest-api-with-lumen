<?php

namespace Tests\Endpoints;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;


class UsersTest extends \TestCase
{
    use DatabaseMigrations;

    public function testGettingAllUsers()
    {
        // without authentication should give 401
        $this->call('GET', '/users');
        $this->assertResponseStatus(401);

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->call('GET', '/users');
        $this->assertResponseOk();

        // test json response
        $this->seeJson(['email' => $user->email]);
    }

    public function testGettingSpecificUser()
    {

        // without authentication should give 401
        $this->call('GET', '/users/12345');
        $this->assertResponseStatus(401);

        $user = factory(User::class)->create();

        // authenticate
        $this->actingAs($user);

        // should work
        $this->call('GET', '/users/'.$user->uid);
        $this->assertResponseStatus(200);

        // test json response
        $this->seeJson(['email' => $user->email]);

        // accessing invalid user should give 404
        $this->call('GET', '/users/13232323');
        $this->assertResponseStatus(404);
    }

    public function testCreatingUser()
    {
        // without authentication should give 401 Unauthorized
        $this->call('POST', '/users', []);
        $this->assertResponseStatus(401);

        $user = factory(User::class)->make();
        $this->actingAs($user);

        // empty data should give 400 invalid fields error
        $this->call('POST', '/users', []);
        $this->assertResponseStatus(400);

        // should work now
        $this->call('POST', '/users', [
            'email'     => 'test@test.com',
            'firstName' => 'first',
            'lastName'  => 'last'
        ]);
        $this->assertResponseStatus(201);

        // same email should give 400 invalid
        $this->call('POST', '/users', [
            'email'     => 'test@test.com',
            'firstName' => 'first2',
            'lastName'  => 'last2'
        ]);
        $this->assertResponseStatus(400);
    }

    public function testUpdatingUser()
    {
        $user = factory(User::class)->create();

        // without authentication should give 401 Unauthorized
        $this->call('PUT', '/users/'.$user->uid, []);
        $this->assertResponseStatus(401);

        // authenticate
        $this->actingAs($user);

        $this->call('PUT', '/users/'.$user->uid, [
            'firstName' => 'updated_first'
        ]);
        $this->assertResponseOk();

        $this->call('PUT', '/users/234324', [
            'firstName' => 'updated_first'
        ]);
        $this->assertResponseStatus(404);
    }

    public function testDeletingUser()
    {

        // without authentication should give 401
        $this->call('DELETE', '/users/12345');
        $this->assertResponseStatus(401);

        $user = factory(User::class)->create();

        // authenticate
        $this->actingAs($user);

        // should work
        $this->call('DELETE', '/users/'.$user->uid);
        $this->assertResponseStatus(204);

        // deleting invalid user should give 404
        $this->call('GET', '/users/13232323');
        $this->assertResponseStatus(404);
    }
}