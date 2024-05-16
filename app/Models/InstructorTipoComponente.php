<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorTipoComponente extends Model
{
    protected $table = 'teachers_components_type';
    use HasFactory;
    protected $fillable = [
        'teachers_id',
        'components_type_id'
    ];
    
    // Relacion con teacher
    public function teacher()
    {
        return $this->belongsTo('App\Models\Instructor', 'teachers_id', 'id');
    }

    // Relacion con coordinacion
    public function coordinacion()
    {
        return $this->belongsTo('App\Models\Coordinacion', 'components_type_id', 'id');
    }

}
