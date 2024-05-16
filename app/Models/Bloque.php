<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloque extends Model
{
    protected $table = 'blocks';

    use HasFactory;

    protected $fillable = [
        'day_id',
        'time_start',
        'time_end',
        'state'
    ];

    // Relación con jornada
    public function jornada()
    {
        return $this->belongsTo('App\Models\Jornada', 'day_id', 'id');
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
