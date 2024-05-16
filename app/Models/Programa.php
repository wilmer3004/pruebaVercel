<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'name',
        'description',
        'coordination_id',
        'program_type_id',
        'duration',
        'color',
        'state'
    ];

    // Relación con coordinación
    public function coordinacion()
    {
        return $this->belongsTo('App\Models\Coordinacion', 'coordination_id', 'id');
    }

    // Relación con Tipo programa
    public function tipoprograma()
    {
        return $this->belongsTo('App\Models\TipoPrograma', 'program_type_id', 'id');
    }

    // Relacion con componenetes
    public function componentes()
    {
        return $this->hasMany('App\Models\Componente', 'program_id', 'id');
    }

    // Relación con ficha
    public function fichas()
    {
        return $this->hasMany('App\Models\Fichas', 'program_id', 'id');
    }

    // Relacion con Components_Programs
    public function componente_programa()
    {
        return $this->hasMany('App\Models\ComponenteProgramas', 'id', 'program_id');
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
