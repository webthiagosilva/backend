<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Logout the authenticated user.
     *
     * header Authorization required The Bearer token.
     *
     * @responseFile responses/auth.logout.post.json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'success' => true,
                'message' => __('auth.logout.success'),
            ],Response::HTTP_OK);
        }
    }
}
