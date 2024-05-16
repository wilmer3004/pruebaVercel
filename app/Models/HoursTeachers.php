<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoursTeachers extends Model
{

    protected $table = 'hours_teachers';

    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'month',
        'hours_worked',
    ];

    //Relación con instructor
    public function instructor(){
        return $this->belongsTo('App\Models\Instructor','teacher_id','id');
    }


}
