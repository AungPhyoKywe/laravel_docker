<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @var User
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $user  = User::all();

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User List successfully!',
            ],
            'data' => [
                'user' => $user,
            ],
        ],Response::HTTP_OK);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);

        if (is_null($user)) {

            return response()->json([
                'success' => false,
                'message' => "User Not Found",
            ], Response::HTTP_NOT_FOUND);
        }

        $user->update($request->validated());

        return response()->json([
            'meta' => [
                'code' => 201,
                'status' => 'success',
                'message' => 'User Updated successfully!',
            ],
            'data' => [
                'user' => $user,
            ],
        ],Response::HTTP_CREATED);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (is_null($user)) {

            return response()->json([
                'success' => false,
                'message' => "User Not Found",
            ], Response::HTTP_NOT_FOUND);
        }
        $user->delete();

        return response()->json([

            'code' => 201,
            'status' => 'success',
            'message' => 'User Deleted successfully!',

        ],Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $user  = User::find($id);

        if (is_null($user)) {

            return response()->json([
                'success' => false,
                'message' => "User Not Found",
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Get User Details successfully!',
            ],
            'data' => [
                'user' => $user,
            ],
        ],Response::HTTP_OK);
    }

    public function me()
    {
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User fetched successfully!',
            ],
            'data' => [
                'user' => auth()->user(),
            ],
        ]);
    }
}
