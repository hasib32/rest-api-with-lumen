<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepository;

class AccessTokenController extends Controller
{
    /**
     * Instance of UserRepository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Constructor
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        parent::__construct();
    }

    /**
     * Since, with Laravel|Lumen passport doesn't restrict
     * a client requesting any scope. we have to restrict it.
     * http://stackoverflow.com/questions/39436509/laravel-passport-scopes
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createAccessToken(Request $request)
    {
        $inputs = $request->all();

        $user = null;
        if (isset($inputs['username']) && $inputs['grant_type'] == 'password') {
            $user = $this->userRepository->findOneBy(['email' => $inputs['username']]);
        }

        if ($user instanceof User) {
            // user with basic role can only request for basic scope
            if ($user->role === User::BASIC_ROLE) {
                $inputs['scope'] = 'basic';
            }
        } else {
            // client_credentials grant can only request for basic scope
            $inputs['scope'] = 'basic';
        }

        $tokenRequest = $request->create('/oauth/token', 'post', $inputs);

        // forward the request to the oauth token request endpoint
        return app()->dispatch($tokenRequest);
    }
}