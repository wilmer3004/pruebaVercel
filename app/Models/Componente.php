<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{

    protected $table = 'components';

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'component_type_id',
        'quarter_id',
        'total_hours',
        'state'
    ];

    // Relacion con tipo componentes
    public function tipo()
    {
        return $this->belongsTo('App\Models\TipoComponente', 'component_type_id', 'id');
    }

    // Relacion con trimestre
    public function trimestre()
    {
        return $this->belongsTo('App\Models\Trimestre', 'quarter_id', 'id');
    }

    // Relación con competencias
    public function competencias()
    {
        return $this->hasMany('App\Models\Competencia', 'component_id', 'id');
    }

    // Relación con eventos
    public function evento()
    {
        return $this->belongsTo('App\Models\Evento', 'component_id', 'id');
    }

    // Relación con instructores
    public function instructores()
    {
        return $this->belongsToMany('App\Models\Instructor', 'components_teachers', 'component_id', 'teacher_id');
    }

    // Relacion con Components_Programs
    public function componente_programa()
    {
        return $this->hasMany('App\Models\ComponenteProgramas', 'id', 'component_id');
    }
    // Relacion con skills
    public function skill(){
        return $this->hasMany('App\Models\Skills', 'component_id', 'id');
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
