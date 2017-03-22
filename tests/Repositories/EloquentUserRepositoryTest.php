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

    public function testFindBy()
    {
        //get total users of this resource
        $totalUsers = User::all()->count();

        //first, check if it returns all users without criteria
        $users = $this->eloquentUserRepository->findBy([]);
        $this->assertCount($totalUsers, $users);
        //@todo need to add more tests
    }

    public function testUpdate()
    {
        $testUser = factory(User::class)->create([
            'firstName' => 'test_first',
            'lastName'  => 'test_last'
        ]);

        // First, test user instance
        $user = $this->eloquentUserRepository->findOne($testUser->uid);
        $this->assertInstanceOf(User::class, $user);

        // Update user
        $this->eloquentUserRepository->update($testUser, [
            'firstName' => 'updated first_name',
            'lastName'  => 'updated last_name'
        ]);

        // Fetch the user again
        $user = $this->eloquentUserRepository->findOne($testUser->uid);
        $this->assertEquals('updated first_name', $user->firstName);
        $this->assertEquals('updated last_name', $user->lastName);
        $this->assertNotEquals('test_first', $user->firstName);
    }

    public function testDelete()
    {
        $testUser = factory(User::class)->create();

        $isDeleted = $this->eloquentUserRepository->delete($testUser);
        $this->assertTrue($isDeleted);

        // confirm deleted
        $user = $this->eloquentUserRepository->findOne($testUser->uid);
        $this->assertNull($user);
    }
}