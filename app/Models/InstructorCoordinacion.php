<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorCoordinacion extends Model
{
    protected $table = 'teachers_coordinations';
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'coordination_id'
    ];
    
    // Relacion con teacher
    public function teacher()
    {
        return $this->belongsTo('App\Models\Instructor', 'teacher_id', 'id');
    }

    // Relacion con coordinacion
    public function coordinacion()
    {
        return $this->belongsTo('App\Models\Coordinacion', 'coordination_id', 'id');
    }
}