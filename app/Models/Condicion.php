<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condicion extends Model
{

    protected $table = 'conditions';

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'state'
    ];

    // Relación con instructores
    public function instructores()
    {
        return $this->belongsToMany('App\Models\Instructor', 'conditions_teacher', 'condicion_id', 'instructor_id');
    }

    // Relación con condicion hora
    public function condicionhora()
    {
        return $this->hasMany('App\Models\CondicionHora', 'condition_id', 'id');
    }

    //Relacion con ConditionTeacher
    public function conditionTeacher(){
        return $this->hasMany('App\Models\ConditionTeacher','id','condition_id');
    }

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
