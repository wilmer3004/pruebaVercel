<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'headquarters';

    protected $fillable = [
        'name',
        'adress',
        'environment_capacity',
        'floors',
        'state'
    ];

    // Relacion con Ambientes
    public function ambientes()
    {
        return $this->hasMany('App\Models\Ambiente', 'headquarter_id', 'id');
    }

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

    protected function adress(): Attribute
    {
        return new Attribute(

            // Accesor
            get: fn ($value) => ucwords($value),

            // mutador
            set: fn ($value) => strtolower($value),

        );
    }
}
