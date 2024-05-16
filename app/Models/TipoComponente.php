<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoComponente extends Model
{
    protected $table = 'components_type';

    use HasFactory;

    protected $fillable = [
        'name',
        'state'
    ];

    // Relacion con AmbienteComponente
    public function ambienteComponentes()
    {
        return $this->hasMany('App\Models\AmbienteComponente', 'component_type_id', 'id');
    }

    public function coordinacion()
    {
        return $this->belongsTo('App\Models\Coordinacion', 'components_type_id', 'id');
    }

    // Accesor y Mutador para el atributo nombre
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
