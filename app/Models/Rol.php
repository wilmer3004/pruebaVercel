<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{

    protected $table = 'roles';

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'state'
    ];

    //Accesor y Mutador para el atributo nombre
    protected function name(): Attribute
    {
        return new Attribute(

            // Accesor
            get: fn ($value) => ucwords($value),

            // mutador
            set: fn ($value) => strtolower($value),

        );
    }

    // Relación muchos a muchos con User
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'users_roles', 'rol_id', 'user_id');
    }
}
