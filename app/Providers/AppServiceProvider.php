<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        View::composer('*', function ($view) {
            $user = Auth::user();
            $role = '';

            if ($user && $user->roleUser === 'operatorDesa') {
                $role = 'Operator Desa';
            } elseif ($user && $user->roleUser === 'operatorKecamatan') {
                $role = 'Operator Kecamatan';
            } elseif ($user && $user->roleUser === 'opDinDafduk') {
                $role = 'Operator Dafduk';
            } elseif ($user && $user->roleUser === 'opDinCapil') {
                $role = 'Operator Capil';
            } elseif ($user && $user->roleUser === 'admin') {
                $role = 'Admin';
            } elseif ($user && $user->roleUser === 'superadmin') {
                $role = 'Superadmin';
            } else {
                $role = '';
            }

            $view->with('role', $role);
        });
    }
}
