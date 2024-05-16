<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponenteProgramas extends Model
{

    protected $table = 'components_programs';

    use HasFactory;

    protected $primaryKey = null; // Indica que la tabla no tiene una columna "id"
    public $incrementing = false; // Indica que la clave primaria no es autoincremental
    protected $fillable = [
        'component_id',
        'program_id',
    ];

    // Relación con Componente
    public function componente()
    {
        return $this->belongsTo('App\Models\Componente', 'component_id', 'id');
    }

    // Relación con Programa
    public function programa()
    {
        return $this->belongsTo('App\Models\Programa', 'program_id', 'id');
    }
}
