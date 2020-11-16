<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isStudent',function($user){
            return $user->roll_flag == 'st';
        });

        Gate::define('isTeacher',function($user){
            return $user->roll_flag == 'te';
        });

        Gate::define('isAdmin',function($user){
            return $user->roll_flag == 'ad';
        });
    }
}
