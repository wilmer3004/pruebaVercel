<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'offers';

    use HasFactory;


    protected $fillable = [
        'name',
        'state'
    ];

    // Relación con ficha
    public function fichas()
    {
        return $this->hasMany('App\Models\Fichas', 'offer_id', 'id');
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
