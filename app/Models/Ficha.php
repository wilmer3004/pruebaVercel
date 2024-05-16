<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{

    protected $table = 'study_sheets';

    use HasFactory;

    protected $fillable = [
        'number',
        'num',
        'program_id',
        'num_trainnies',
        'day_id',
        'offer_id',
        'quarter_id',
        'start_lective',
        'end_lective',
        'state'
    ];

    // Relación con programa
    public function programa()
    {
        return $this->belongsTo('App\Models\Programa', 'program_id', 'id');
    }

    // Relación con jornada
    public function jornada()
    {
        return $this->belongsTo('App\Models\Jornada', 'day_id', 'id');
    }

    // Relación con oferta
    public function oferta()
    {
        return $this->belongsTo('App\Models\Oferta', 'offer_id', 'id');
    }

    // Relación con trimestre
    public function trimestre()
    {
        return $this->belongsTo('App\Models\Trimestre', 'quarter_id', 'id');
    }

    // Relación con evento
    public function evento()
    {
        return $this->belongsTo('App\Models\Evento', 'study_sheet_id', 'id');
    }
}
