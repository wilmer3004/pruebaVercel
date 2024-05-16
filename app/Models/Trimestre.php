<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    protected $table = 'quarters';

    use HasFactory;

    protected $fillable = [
        'name',

    ];

    // Relación con componentes
    public function componentes()
    {
        return $this->hasMany('App\Models\Componente', 'quarter_id', 'id');
    }

    // Relación con ficha
    public function fichas()
    {
        return $this->hasMany('App\Models\Fichas', 'quarter_id', 'id');
    }

    //Accesor y Mutador para el atributo nombre
    protected function name(): Attribute
    {
        return new Attribute(

            // Accesor
            get: fn ($value) => strtoupper($value),

            // mutador
            set: fn ($value) => strtolower($value),

        );
    }

}
