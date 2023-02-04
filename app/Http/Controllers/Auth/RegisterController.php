<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\Auth\UserTokenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \App\Http\Requests\Auth\UserRegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        try {
            User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('auth.register.error'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => __('auth.register.success'),
        ], Response::HTTP_CREATED);
    }

    public function token(UserTokenRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        $token = $user->createToken('auth_token');

        return response()->json([
            'success' => true,
            'message' => __('auth.token.success'),
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
        ], Response::HTTP_OK);
    }
}
