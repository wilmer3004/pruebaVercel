<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoraLaboral extends Model
{

    protected $table = 'working_hours';

    use HasFactory;

    protected $fillable = [
        'contract_id',
        'dh_min',
        'dh_max',
        'mh_min',
        'mh_max'
    ];

    // Relación muchos a uno con Instructor
    public function instructores()
    {
        return $this->hasMany('App\Models\Instructor');
    }

    // Relacion con hora laboral
    public function contrato()
    {
        return $this->belongsTo('App\Models\Contrato', 'contract_id', 'id');
    }
}
