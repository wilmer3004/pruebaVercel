<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PhpParser\Node\Expr\FuncCall;

class User extends Authenticatable
{
    protected $table = 'users';

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación uno a uno con Persona
    public function persona()
    {
        return $this->belongsTo('App\Models\Persona', 'id', 'user_id');
    }

    // Relación muchos a muchos con Rol
    public function roles()
    {
        return $this->belongsToMany('App\Models\Rol', 'users_roles', 'user_id', 'rol_id');
    }

    // Relación uno a uno con instructor
    public function instructor()
    {
        return $this->belongsTo('App\Models\Instructor', 'id', 'user_id');
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->count() > 0;
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->count() > 0;
    }
}
