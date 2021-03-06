<?php

use Laravel\Passport\Passport;
use Cartalyst\Extensions\Extension;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Laravel\Passport\Console\ClientCommand;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Illuminate\Contracts\Foundation\Application;
use Cartalyst\Permissions\Container as Permissions;
use Illuminate\Contracts\Routing\Registrar as Router;
use Carbonodev\Oauth\Models\UserOverrideServiceProvider;

Extension::installed(
    function (Extension $extension) {
        if ($extension->getSlug() == 'carbonodev/oauth') {

            \Illuminate\Console\Application::starting(
                function ($artisan) {
                    $artisan->resolveCommands([KeysCommand::class]);
                }
            );
            app('Illuminate\Contracts\Console\Kernel')->call('migrate', ['--path' => 'vendor/laravel/passport/database/migrations']);
            app('Illuminate\Contracts\Console\Kernel')->call('passport:keys');
        }
    }
);

Extension::enabled(
    function (Extension $extension) {
        if ($extension->getSlug() == 'carbonodev/oauth') {

            \Illuminate\Console\Application::starting(
                function ($artisan) {
                    $artisan->resolveCommands([ClientCommand::class]);
                }
            );
            app('Illuminate\Contracts\Console\Kernel')->call('passport:client', ['--personal' => true, '--name' => config('app.name') . ' Personal Access Client']);
            app('Illuminate\Contracts\Console\Kernel')->call('passport:client', ['--password' => true, '--name' => config('app.name') . ' Password Grant Client']);
        }
    }
);

return [

    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    |
    | This is your extension name and it is only required for
    | presentational purposes.
    |
    */

    'name' => 'Oauth',

    /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    |
    | This is your extension unique identifier and should not be changed as
    | it will be recognized as a new extension.
    |
    | Ideally, this should match the folder structure within the extensions
    | folder, but this is completely optional.
    |
    */

    'slug' => 'carbonodev/oauth',

    /*
    |--------------------------------------------------------------------------
    | Author
    |--------------------------------------------------------------------------
    |
    | Because everybody deserves credit for their work, right?
    |
    */

    'author' => 'Carbono Dev',

    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    |
    | One or two sentences describing the extension for users to view when
    | they are installing the extension.
    |
    */

    'description' => 'Enables oauth support via passport',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Version should be a string that can be used with version_compare().
    | This is how the extensions versions are compared.
    |
    */

    'version' => '0.1.0',

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | List here all the extensions that this extension requires to work.
    | This is used in conjunction with composer, so you should put the
    | same extension dependencies on your main composer.json require
    | key, so that they get resolved using composer, however you
    | can use without composer, at which point you'll have to
    | ensure that the required extensions are available.
    |
    */

    'requires' => [
        'platform/access',
        'platform/users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoload Logic
    |--------------------------------------------------------------------------
    |
    | You can define here your extension autoloading logic, it may either
    | be 'composer', 'platform' or a 'Closure'.
    |
    | If composer is defined, your composer.json file specifies the autoloading
    | logic.
    |
    | If platform is defined, your extension receives convetion autoloading
    | based on the Platform standards.
    |
    | If a Closure is defined, it should take two parameters as defined
    | bellow:
    |
    |   object \Composer\Autoload\ClassLoader      $loader
    |   object \Illuminate\Foundation\Application  $app
    |
    | Supported: "composer", "platform", "Closure"
    |
    */

    'autoload' => 'composer',

    /*
    |--------------------------------------------------------------------------
    | Service Providers
    |--------------------------------------------------------------------------
    |
    | Define your extension service providers here. They will be dynamically
    | registered without having to include them in app/config/app.php.
    |
    */

    'providers' => [
        UserOverrideServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Closure that is called when the extension is started. You can register
    | any custom routing logic here.
    |
    | The closure parameters are:
    |
    |   object \Illuminate\Contracts\Routing\Registrar  $router
    |   object \Cartalyst\Extensions\ExtensionInterface  $extension
    |   object \Illuminate\Contracts\Foundation\Application  $app
    |
    */

    'routes' => function (Router $router, ExtensionInterface $extension, Application $app) {
        Passport::routes();

        $router->prefix('api')
            ->middleware('api')
            ->namespace("Carbonodev\Oauth\Controllers\Api")
            ->group(realpath(__DIR__.'/routes/api.php'));
    },

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Register here all the permissions that this extension has. These will
    | be shown in the user management area to build a graphical interface
    | where permissions can be selected to allow or deny user access.
    |
    | For detailed instructions on how to register the permissions, please
    | refer to the following url https://cartalyst.com/manual/permissions
    |
    | The closure parameters are:
    |
    |   object \Cartalyst\Permissions\Container  $permissions
    |   object \Illuminate\Contracts\Foundation\Application  $app
    |
    */

    'permissions' => function (Permissions $permissions, Application $app) {

    },

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Register here all the settings that this extension has.
    |
    | For detailed instructions on how to register the settings, please
    | refer to the following url https://cartalyst.com/manual/settings
    |
    | The closure parameters are:
    |
    |   object \Cartalyst\Settings\Repository  $settings
    |   object \Illuminate\Contracts\Foundation\Application  $app
    |
    */

    'settings' => function (Settings $settings, Application $app) {

    },

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | Closure that is called when the extension is started. You can register
    | all your custom widgets here. Of course, Platform will guess the
    | widget class for you, this is just for custom widgets or if you
    | do not wish to make a new class for a very small widget.
    |
    */

    'widgets' => function () {

    },

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    | You may specify the default various menu hierarchy for your extension.
    | You can provide a recursive array of menu children and their children.
    | These will be created upon installation, synchronized upon upgrading
    | and removed upon uninstallation.
    |
    | Menu children are automatically put at the end of the menu for extensions
    | installed through the Operations extension.
    |
    | The default order (for extensions installed initially) can be
    | found by editing app/config/platform.php.
    |
    */

    'menus' => [

        'admin' => [

        ],

        'main' => [

        ],

    ],

];
