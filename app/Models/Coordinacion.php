<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinacion extends Model
{
    protected $table = 'coordinations';
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'multi_technique',
        'state'
    ];

    // Relación muchos a uno
    public function instructores()
    {
        return $this->hasMany('App\Models\Instructor', 'coordination_id', 'id');
    }

    // Relación con programa
    public function programas()
    {
        return $this->hasMany('App\Models\Programa', 'coordination_id', 'id');
    }
    // Relación con InstructorCoordinacion
    public function instructorCoordinaciones()
    {
        return $this->hasMany('App\Models\InstructorCoordinacion', 'coordination_id', 'id');
    }

    // Relación con ComponentsTeacher
    public function componentsTeachers()
    {
        return $this->hasMany('App\Models\ComponentsTeacher', 'coordination_id', 'id');
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
