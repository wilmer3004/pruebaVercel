<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{

    protected $table = 'events';

    use HasFactory;

    protected $fillable = [
        'environment_id',
        'component_id',
        'study_sheet_id',
        'study_sheet_state',
        'environment_state',
        'start',
        'end',
        'total_hours',
        'teacher_id'
    ];

    // Relación con fichas
    public function fichas()
    {
        return $this->hasMany('App\Models\Ficha', 'study_sheet_id', 'id');
    }

    // Relación con componentes
    public function componentes()
    {
        return $this->hasMany('App\Models\Componente', 'component_id', 'id');
    }

    // Relación con ambientes
    public function ambientes()
    {
        return $this->hasMany('App\Models\Ambiente', 'environment_id', 'id');
    }

    protected function component_type(): Attribute
    {
        return new Attribute(
            set: fn ($value) => strtolower($value),
        );
    }
}
