<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentsTeacher extends Model
{

    protected $table = 'teachers_coordinations';

    use HasFactory;

    //Relacion con instructor
    public function teacher(){
        return $this->belongsTo('App\Models\Instructor','teacher_id','id');
    }

    //Relacion con coordinacion
    public function coordination(){
        return $this->belongsTo('App\Models\Coordinacion', 'coordination_id', 'id');
    }

}

