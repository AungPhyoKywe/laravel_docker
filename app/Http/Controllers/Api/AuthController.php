<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Auth;
use App\Models\User;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var User
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        $image_path = $request->file('image')->store('image', 'public');
        $validated['image'] = url('/storage/'.$image_path);
        $validated['password'] = bcrypt($validated['password']);
        $user = $this->user::create($validated);

        $token = auth()->login($user);

        return response()->json([
            'meta' => [
                'code' => 201,
                'status' => 'success',
                'message' => 'User created successfully!',
            ],
            'data' => [
                'user' => $user,
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => Auth::factory()->getTTL() * 60,    // get token expires in seconds
                ],
            ],
        ],Response::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request)
    {
        $token = auth()->attempt($request->validated());

        if ($token) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Quote fetched successfully.',
                ],
                'data' => [
                    'user' => auth()->user(),
                    'access_token' => [
                        'token' => $token,
                        'type' => 'Bearer',
                        'expires_in' => Auth::factory()->getTTL() * 60,
                    ],
                ],
            ],Response::HTTP_OK);
        }

        return response()->json([
            'code' => 400,
            'status' => 'fail to login',
            'message' => 'Invalid Username or password',
        ],Response::HTTP_BAD_REQUEST);
    }

    public function logout()
    {
        $token = JWTAuth::getToken();
        $invalidate = JWTAuth::invalidate($token);

        if ($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ],Response::HTTP_OK);
        }
    }
}
