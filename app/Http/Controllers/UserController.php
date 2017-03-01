<?php //app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Instance of UserRepository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Assign the validatorName that will be used for validation
     *
     * @var string
     */
    protected $validatorName = 'User';

    /**
     * Constructor
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->findBy($request->all());

        return response()->json(['data' => $users], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function show($id)
    {
        $user = $this->userRepository->findOne($id);

        if (!$user instanceof User) {
            return response()->json(['message' => "The user with id {$id} doesn't exist"], 404);
        }

        return response()->json(['data' => $user], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function store(Request $request)
    {
        $user = $this->userRepository->save($request->all());

        if (!$user instanceof User) {
            return response()->json(['message' => "Error occurred on creating user"], 500);
        }

        return response()->json(['data' => $user], 201);
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
        $user = $this->userRepository->findOne($id);

        if (!$user instanceof User) {
            return response()->json(['message' => "The user with id {$id} doesn't exist"], 404);
        }

        $inputs = $request->all();

        $user = $this->userRepository->update($user, $inputs);

        return response()->json(['data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findOne($id);

        if (!$user instanceof User) {
            return response()->json(['message' => "The user with id {$id} doesn't exist"], 404);
        }

        $this->userRepository->delete($user);

        return response()->json(null, 204);
    }
}