<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Instructor extends Model
{

    protected $table = "teachers";

    use HasFactory;

    protected $fillable = [
        'user_id',
        'contract_id',
        'total_hours',
        'state' // activo o inactivo
    ];

    // Relación con contrato
    public function contrato()
    {
        return $this->belongsTo('App\Models\Contrato', 'contract_id', 'id');
    }

    // Relación con condiciones
    public function condiciones()
    {
        return $this->belongsToMany('App\Models\Condicion', 'conditions_teacher', 'teacher_id', 'condition_id');
    }

    // Relacion muchos a muchos con componentes
    public function componentes()
    {
        return $this->belongsToMany('App\Models\Componente', 'components_teachers', 'teacher_id', 'component_id');
    }

    // Relacion de mucho a mucho con coordinacion
    public function coordinaciones()
    {
        return $this->belongsToMany(Coordinacion::class, 'teachers_coordinations', 'teacher_id', 'coordination_id');
    }

    // Relacion de muchos a muchos con tipo de componente
    public function tipoComponente()
    {
        return $this->belongsToMany(TipoComponente::class, 'teachers_components_type', 'teachers_id', 'components_type_id');
    }

    // Relación uno a uno con User
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
        //Relacion con ConditionTeacher
        public function conditionTeacher(){
            return $this->hasMany('App\Models\ConditionTeacher','id','teacher_id');
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

    //Accesor y Mutador para el atributo apellido
    protected function lastname(): Attribute
    {
        return new Attribute(

            // Accesor
            get: fn ($value) => ucwords($value),

            // mutador
            set: fn ($value) => strtolower($value),

        );
    }
}
