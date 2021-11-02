<?php

namespace MakiDizajnerica\Permissions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use MakiDizajnerica\Permissions\Models\Department;
use MakiDizajnerica\Permissions\Models\Permission;
use MakiDizajnerica\Permissions\Observers\DepartmentObserver;
use MakiDizajnerica\Permissions\Contracts\BelongsToDepartments as BelongsToDepartmentsContract;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/permissions.php', 'permissions'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        $this->configurePublishing();
        $this->definePermissionGates();
        $this->registerDepartmentObserver();
    }

    /**
     * @return void
     */
    protected function configurePublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/permissions.php' => config_path('permissions.php')
        ], 'permissions-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ], 'permissions-migrations');
    }

    /**
     * Define permission's gates.
     * 
     * @return void
     */
    protected function definePermissionGates()
    {
        Gate::before(function ($user) {
            if ($user instanceof BelongsToDepartmentsContract &&
                $user->isAdministrator()) {
                return true;
            }
        });

        if (Schema::hasTable('permissions')) {
            $permissions = Permission::all()->pluck('slug')->toArray();

            foreach ($permissions as $permission) {
                Gate::define($permission, function ($user) use ($permission) {
                    if ($user->hasPermissionThroughDepartmentsTo($permission)) {
                        return true;
                    }

                    return $user->hasPermissionTo($permission);
                });
            }
        }
    }

    /**
     * @return void
     */
    protected function registerDepartmentObserver()
    {
        Department::observe(DepartmentObserver::class);
    }
}
