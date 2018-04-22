<?php namespace Carbonodev\Oauth\Models;

use Carbonodev\Oauth\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbonodev\Oauth\Guards\Sentinel;
use Cartalyst\Support\ServiceProvider;
use Platform\Access\Providers\AccessServiceProvider;

class UserOverrideServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->overrideAuthModels();
        $this->registerSentinelGuard();
        $this->overrideSentinelModels();
    }

    /**
     * Overrides the Auth models and guards.
     *
     * @return void
     */
    protected function registerSentinelGuard()
    {
        config(['auth.guards.web.driver' => 'sentinel']);
        Auth::extend(
            'sentinel',
            function ($app, $name, array $config) {
                return new Sentinel(Auth::createUserProvider($config['provider']));
            }
        );


        $this->app->rebinding(
            'request',
            function ($app, $request) {
                $request->setUserResolver(
                    function () use ($app) {
                        return $app['auth']->user();
                    }
                );
            }
        );
    }

    /**
     * Overrides the Auth models and guards.
     *
     * @return void
     */
    protected function overrideAuthModels()
    {
        config(['auth.guards.api.driver' => 'passport']);
        config(['auth.providers.users.model' => User::class]);
    }

    /**
     * Overrides the Sentinel models.
     *
     * @return void
     */
    protected function overrideSentinelModels()
    {
        // Get the roles and users models
        $rolesModel = get_class($this->app['Platform\Roles\Models\Role']);
        $usersModel = User::class;

        // Set our models within Sentinel
        $this->app['sentinel.roles']->setModel($rolesModel);
        $this->app['sentinel.users']->setModel($usersModel);
        $this->app['sentinel.persistence']->setUsersModel($usersModel);

        // Override the users model on the roles model
        $rolesModel::setUsersModel($usersModel);

        // Override the roles model on the users model
        $usersModel::setRolesModel($rolesModel);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
