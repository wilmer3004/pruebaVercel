<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbienteComponente extends Model
{
    protected $table = 'environment_components';
    use HasFactory;
    protected $fillable = [
        'environment_id',
        'component_type_id'
    ];
    
    // Relacion con Ambiente (enviroments)
    public function enviroments()
    {
        return $this->belongsTo('App\Models\Ambiente', 'environment_id', 'id');
    }

    // Relacion con Tipo de componetne (components_type)
    public function components_type()
    {
        return $this->belongsTo('App\Models\TipoComponente', 'component_type_id', 'id');
    }
}