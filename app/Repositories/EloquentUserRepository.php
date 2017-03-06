<?php //app/Repositories/EloquentUserRepository.php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class EloquentUserRepository extends AbstractEloquentRepository implements UserRepository
{
    /**
     * Model name
     *
     * @var string
     */
    protected $modelName = User::class;


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
}