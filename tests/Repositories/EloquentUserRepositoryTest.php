<?php

namespace Tests\Repositories;

use App\Models\User;
use App\Repositories\EloquentUserRepository;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $this->eloquentUserRepository = new EloquentUserRepository(new User());
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
        // when instantiate the repo, logged in as Admin user. So, that we can search any user
        $adminUser = factory(User::class)->make(['role' => User::ADMIN_ROLE]);
        Auth::shouldReceive('user')->andReturn($adminUser);
        $eloquentUserRepository = new EloquentUserRepository(new User());

        //get total users of this resource
        $totalUsers = User::all()->count();

        //first, check if it returns all users without criteria
        $users = $eloquentUserRepository->findBy([]);
        $this->assertCount($totalUsers, $users);

        //create a user and findBy that using user's firstName
        factory(User::class)->create(['firstName' => 'Pappu']);
        $users = $eloquentUserRepository->findBy(['firstName' => 'Pappu']);
        //test instanceof
        $this->assertInstanceOf(LengthAwarePaginator::class, $users);
        $this->assertNotEmpty($users);

        //check with multiple criteria
        $searchCriteria = ['zipCode'     => '11121', 'username' => 'jobberAli'];
        $previousTotalUsers = $eloquentUserRepository->findBy($searchCriteria)->count();
        $this->assertEmpty($previousTotalUsers);

        factory(User::class)->create($searchCriteria);
        $newTotalUsers = $eloquentUserRepository->findBy($searchCriteria)->count();
        $this->assertNotEmpty($newTotalUsers);

        //with basic user's permission, create a user and findBy using that user's firstName
        factory(User::class)->create(['firstName' => 'Jobber']);
        $users = $this->eloquentUserRepository->findBy(['firstName' => 'Jobber']);
        $this->assertEmpty($users);
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
