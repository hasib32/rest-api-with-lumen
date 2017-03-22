<?php

namespace Tests\Repositories;

use App\Models\User;
use App\Repositories\EloquentUserRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;

class EloquentUserRepositoryTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * @var EloquentUserRepository
     */
    protected $eloquentUserRepository;

    public function setup()
    {
        parent::setUp();

        $this->eloquentUserRepository = new EloquentUserRepository();
    }

    public function testCreateUser()
    {
        $testUserArray = factory(User::class)->make()->toArray();
        $user = $this->eloquentUserRepository->save($testUserArray);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($testUserArray['email'], $user->email);
    }

    public function testFindOne()
    {
        $testUser = factory(User::class)->create();

        //first, check if it returns valid user
        $user = $this->eloquentUserRepository->findOne($testUser->uid);
        $this->assertInstanceOf(User::class, $user);

        //now check it returns null for gibberish data
        $user = $this->eloquentUserRepository->findOne('giberish');
        $this->assertNull($user);
    }

    public function testFindOneBy()
    {
        $testUser = factory(User::class)->create();

        //first, check if it returns valid user
        $user = $this->eloquentUserRepository->findOneBy(['uid' => $testUser->uid]);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($testUser->email, $user->email);

        //check if it returns valid user, for multiple criteria
        $user = $this->eloquentUserRepository->findOneBy([
            'email'     => $testUser->email,
            'firstName' => $testUser->firstName
        ]);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($testUser->firstName, $user->firstName);

        //now check it returns null for gibberish data
        $user = $this->eloquentUserRepository->findOneBy(['lastName' => 'Test Last']);
        $this->assertNull($user);
    }
}