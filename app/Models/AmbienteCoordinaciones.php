<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbienteCoordinaciones extends Model
{

    protected $table = 'environment_coordinations';

    use HasFactory;

    protected $primaryKey = null; // Indica que la tabla no tiene una columna "id"
    public $incrementing = false; // Indica que la clave primaria no es autoincremental
    protected $fillable = [
        'environment_id',
        'coordination_id',
    ];

    // Relación con Componente
    public function ambiente()
    {
        return $this->belongsTo('App\Models\Ambiente', 'environment_id', 'id');
    }

    // Relación con Programa
    public function coordinacion()
    {
        return $this->belongsTo('App\Models\Coordinacion', 'coordination_id', 'id');
    }
}
