<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    private function jwt(User $user)
    {
        $payload = [
            'iss' => "laravel", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60 // Expiration time
        ];

        $jwt = JWT::encode($payload, config('jwt.key'), 'HS256');

        return $jwt;
    }

    public function register(UserRegisterRequest $request)
    {
        $user = new User();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));

        $user->save();

        return response()->json([
            'success' => true,
            'message' => __('auth.Register.success'),
        ], Response::HTTP_CREATED);
    }

    // public function login(UserLoginRequest $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
    //         $token = $this->jwt($user);

    //         return response()->json([
    //             'success' => true,
    //             'message' => __('auth.signin.success'),
    //             'data' => [
    //                 'token' => $token,
    //                 'user' => $user,
    //             ], Response::HTTP_OK
    //         ]);
    //     }

    //     // if (!$token = Auth::guard()->attempt([$credentials['email'], $credentials['password']])) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => __('auth.signin.failed'),
    //     //     ], Response::HTTP_UNAUTHORIZED);
    //     // }

    //     $user = Auth::guard()->user();

    //     $token = $this->jwt($user);

    //     return response()->json([
    //         'success' => true,
    //         'message' => __('auth.signin.success'),
    //         'data' => [
    //             'token' => $token,
    //             'user' => $user,
    //         ], Response::HTTP_OK
    //     ]);
    // }

    public function login()
    {
    }

    public function logout()
    {
    }

    public function refresh()
    {
    }
}
