<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemplazosInstructor extends Model
{
    protected $table = 'work_updates';

    use HasFactory;

    protected $fillable= [
        'teacher_id',
        'replacement_teacher_id',
        'type_work_update_id',
        'date'
    ];

    //Relacion con instructores
    public function instructor()
    {
        return $this->hasMany('App\Models\Instructor', 'teacher_id', 'id');
    }

    public function instructorRemplazo()
    {
        return $this->hasMany('App\Models\Instructor', 'replacement_teacher_id', 'id');
    }

    //Relacion con TipoExcusaInstructor
    public function tipoExcusaInstructor()
    {
        return $this->hasMany('App\Models\TipoExcusaInstructor', 'type_work_update_id', 'id');
    }
}
