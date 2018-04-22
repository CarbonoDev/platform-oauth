# Platform OAuth

This package adds support for laravel passport and bridges sentinel with the default laravel Auth

## After installation

Change Authentication middleware to `Illuminate\Auth\Middleware\Authenticate`
Add Cors middleware `\Spatie\Cors\Cors::class` to Kernel.php