<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competencia extends Model
{
    protected $table = "skills";

    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'description',
        'component_id'
    ];

    // Relación con componente
    public function componente()
    {
        return $this->belongsTo('App\Models\Componente', 'component_id', 'id');
    }

    //Accesor y Mutador para el atributo nombre
    protected function name(): Attribute
    {
        return new Attribute(

            // Accesor
            get: fn ($value) => ucwords($value),

            // mutador
            set: fn ($value) => strtolower($value),

        );
    }
}
