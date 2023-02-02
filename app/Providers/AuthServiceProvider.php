<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('jwt', function (Request $request) {
            try {
                $authToken = $request->bearerToken();

                if (is_null($authToken)) {
                    throw new UnauthorizedHttpException('unauthorized', 'bearer token is required');
                }

                $tokenPayload = JWT::decode($authToken, config('jwt.key'), 'HS256');

                $user = User::query()
                    ->where('id', $tokenPayload->sub)
                    ->first();

                if (is_null($user)) {
                    Log::error('user not found in the application');
                    throw new UnauthorizedHttpException('unauthorized', 'user not found in the application');
                }

                return $user;
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                return null;
            }
        });
    }
}
