<?php namespace Carbonodev\Oauth\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Attributes\EntityInterface;
use Platform\Attributes\Traits\EntityTrait;
use Platform\Users\Models\User as PlatformUser;
use Cartalyst\Support\Traits\NamespacedEntityTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends PlatformUser implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use HasApiTokens;

    protected $hidden = [
        'activations',
        'password'
    ];

    protected $appends = [
        'activated',
        'picture'
    ];

    public function getPictureAttribute()
    {
        $email_hash = md5(strtolower(trim($this->email)));
        return "http://www.gravatar.com/avatar/$email_hash?d=retro";
    }
}
