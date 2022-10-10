<?php

namespace App\Providers;

use App\Models\Permission;
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
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Define Access Gates for User Permissions

        //$permissions = Permission::all();

        $user = \Auth::user();


        // Auth gates for: User management
        Gate::define('admin_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('tb_access', function ($user) {
            return in_array($user->role_id, [2]);
        });

        Gate::define('team_leader_access', function ($user) {
            return in_array($user->role_id, [3]);
        });

        Gate::define('brand_ambassador_access', function ($user) {
            return in_array($user->role_id, [4]);
        });
        Gate::define('client_access', function ($user) {
            return in_array($user->role_id, [5]);
        });
//        $createCampaign = "Create Campaign";
//        Gate::define($createCampaign, function ($user) {
//            return in_array($user->id, [\Auth::id()]);
//        });

    }
}
