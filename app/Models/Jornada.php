<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    protected $table = 'days';

    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'state'
    ];

    // Relación con bloque
    public function bloque()
    {
        return $this->hasOne('App\Models\Bloque', 'id', 'day_id');
    }

    // Relación con ficha
    public function fichas()
    {
        return $this->hasMany('App\Models\Fichas', 'day_id', 'id');
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
}
