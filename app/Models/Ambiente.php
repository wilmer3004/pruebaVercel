<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambiente extends Model
{

    use HasFactory;

    protected $table = 'environments';

    protected $fillable = [
        'name',
        'headquarter_id',
        'floor',
        'capacity',
        'state'
    ];

    // Relación con Sede
    public function sede()
    {
        return $this->belongsTo('App\Models\Sede', 'headquarter_id', 'id');
    }

    // Relación con eventos
    public function componentes()
    {
        return $this->belongsToMany('App\Models\TipoComponente', 'AmbienteComponente', 'environment_id', 'component_type_id');
    }

    // Relacion

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
