<?php namespace Carbonodev\Oauth\Controllers\Api;

use Carbonodev\Oauth\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AuthenticatedUsersController extends Controller
{
    public function me()
    {
        return response()->json(request()->user());
    }
}
