<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Models\UserRoleRelation;
use Auth;
use App\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use  Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'firstName',
        'lastName',
        'website',
        'logo',
        'phone',
        'email_verified_at',
        'password',
        'university_id',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getRole()
    {
        return $this->hasOneThrough('App\Models\Role', 'App\Models\UserRoleRelation', 'user_id', 'id', 'id', 'role_id');
    }
    public function checkFollow()
    {
        return $this->belongsToMany('App\User', 'following', 'masjid_id', 'user_id');
    }
    /**
     * Check Roles admin here 
     *
     * @var array
     */
    public function isAdmin()
    {
        $role = Role::join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('user_id', Auth::user()->id)
            ->first();
        return $role->name == 'admin' ? true : false;
    }
    public function isUniversity()
    {
        $role = Role::join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('user_id', Auth::user()->id)
            ->first();
        return $role->name == 'university' ? true : false;
    }
    public function isStudent()
    {
        $role = Role::join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('user_id', Auth::user()->id)
            ->first();
        return $role->name == 'student' ? true : false;
    }
}
