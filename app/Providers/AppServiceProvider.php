<?php

namespace App\Providers;

use App\Models\Model;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $rateLimiterResponse = function (Request $request)
        {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Too many attempts. Please try again later',
                ], 429);
            }
            return back()->withErrors(
                [
                'email' => 'Too many attempts. Please try again later.',
                ]
            )->withInput($request->except('password'));
        };

        RateLimiter::for('login', function (Request $request) use ($rateLimiterResponse) {
            return [
                Limit::perMinute(100)->by($request->ip())->response($rateLimiterResponse),
                Limit::perMinute(3)->by($request->input('email'))->response($rateLimiterResponse)
            ];
        });

        RateLimiter::for('password-reset-request', function (Request $request) {
            return [
                Limit::perHour(10)->by($request->ip()),
                Limit::perHour(3)->by($request->input('email')),
            ];
        });

        RateLimiter::for('password-reset', function (Request $request) {
            return [
                Limit::perHour(5)->by($request->ip()),
                Limit::perHour(3)->by($request->input('email')),
            ];
        });

        Password::defaults(function () {
            if($this->app->isLocal()) {
                return Password::min(8);
            }
            return Password::min(8)
                ->mixedCase()
                ->uncompromised()
                ->letters()
                ->numbers()
                ->symbols();
        });
    }
}
