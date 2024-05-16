<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionTeacher extends Model
{
    protected $table = 'conditions_teacher';

    use HasFactory;

    //Relacion con condicion
    public function condicion(){
        return $this->belongsTo('App\Models\Condicion','condition_id','id');
    }

    //Relacion con instructor
    public function instructor(){
        return $this->belongsTo('App\Models\Instructor','teacher_id','id');
    }
}
