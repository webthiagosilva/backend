<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginController extends Controller
{
    /**
     * Generate a new access Login.
     *
     * @bodyParam email string required The email of the user.
     * @bodyParam password string required The password of the user.
     *
     * @responseFile responses/login.post.json
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            throw new UnauthorizedHttpException('Invalid credentials');
        }

        $user = Auth::user();
        $user->tokens()->delete();

        $token = $user->createToken(
            'auth_token',
            ['*'],
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        );

        return response()->json([
            'success' => true,
            'message' => __('auth.login.success'),
            'data' => [
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ],
        ], Response::HTTP_OK);
    }
}
