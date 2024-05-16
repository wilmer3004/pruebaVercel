<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contracts';
    use HasFactory;

    protected $fillable = [
        'name',
        'total_hours',
        'state'
    ];

    // Relacion con instructores
    public function instructores()
    {
        return $this->hasMany('App\Models\Instructor', 'contract_id', 'id');
    }

    // Relacion con hora laboral
    public function horas()
    {
        return $this->hasMany('App\Models\HoraLaboral', 'contract_id', 'id');
    }

    // Relación con condicion hora
    public function condicion_hora()
    {
        return $this->hasMany('App\Models\CondicionHora', 'contract_id', 'id');
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
