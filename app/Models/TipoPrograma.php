<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrograma extends Model
{
    use HasFactory;

    protected $table = 'program_type';

    protected $fillable = [
        'name',
        'state'
    ];

    // Relación con programa
    public function programas()
    {
        return $this->hasMany('App\Models\Programa');
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
