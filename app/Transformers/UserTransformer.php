<?php //app/Transformers/UserTransformer.php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $formattedUser = [
            'uid'                   => $user->uid,
            'firstName'             => $user->firstName,
            'lastName'              => $user->lastName,
            'middleName'            => $user->middleName,
            'username'              => $user->username,
            'email'                 => $user->email,
            'address'               => $user->address,
            'zipCode'               => $user->zipCode,
            'city'                  => $user->city,
            'state'                 => $user->state,
            'country'               => $user->country,
            'phone'                 => $user->phone,
            'mobile'                => $user->mobile,
            'type'                  => $user->type,
            'createdAt'             => (string) $user->created_at,
            'updatedAt'             => (string) $user->updated_at
        ];

        return $formattedUser;
    }
}