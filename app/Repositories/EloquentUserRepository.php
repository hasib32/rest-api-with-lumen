<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Events\UserEvents\UserCreatedEvent;

class EloquentUserRepository extends AbstractEloquentRepository implements UserRepository
{

    /*
     * @inheritdoc
     */
    public function save(array $data)
    {
        // update password
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = parent::save($data);

        // fire user created event
        \Event::fire(new UserCreatedEvent($user));

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function update(Model $model, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $updatedUser = parent::update($model, $data);

        return $updatedUser;
    }

    /**
     * @inheritdoc
     */
    public function findBy(array $searchCriteria = [])
    {
        // Only admin user can see all users
        if ($this->loggedInUser->role !== User::ADMIN_ROLE) {
            $searchCriteria['id'] = $this->loggedInUser->id;
        }

        return parent::findBy($searchCriteria);
    }

    /**
     * @inheritdoc
     */
    public function findOne($id)
    {
        if ($id === 'me') {
            return $this->getLoggedInUser();
        }

        return parent::findOne($id);
    }
}