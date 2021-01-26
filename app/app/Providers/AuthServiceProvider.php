<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\ClassworkTask;
use App\Models\InCharge;
use App\Policies\TeamPolicy;
use App\Policies\ClassworkTaskPolicy;
use App\Policies\InChargePolicy;
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
        ClassworkTask::class => ClassworkTaskPolicy::class,
        InCharge::class => InChargePolicy::class,
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
            return $user->isStudent;
        });

        Gate::define('isTeacher',function($user){
            return $user->isTeacher;
        });

        Gate::define('isAdmin',function($user){
            return $user->isAdmin;
        });
    }
}
