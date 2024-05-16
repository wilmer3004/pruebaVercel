<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoExcusaInstructor extends Model
{
    protected $table = 'type_work_updates';

    use HasFactory;

    protected $fillable= [
        'name',
        'state'
    ];

    // Relacion con Ambientes
    public function WorkUpdate()
    {
        return $this->hasMany('App\Models\RemplazosInstructor', 'type_work_update_id', 'id');
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

    protected function state(): Attribute
    {
        return new Attribute(

            // Accesor
            get: fn ($value) => ucwords($value),

            // mutador
            set: fn ($value) => strtolower($value),

        );
    }
}
