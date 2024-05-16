<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'people';

    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'document',
        'email',
        'user_id',
        'phone',
        'document_type_id'
    ];

    // Relación uno a uno con User
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function documentsType()
    {
        return $this->belongsTo('App\Models\DocumentsType', 'document_type_id', 'id');
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

    //Accesor y Mutador para el atributo apellido
    protected function lastname(): Attribute
    {
        return new Attribute(

            // Accesor
            get: fn ($value) => ucwords($value),

            // mutador
            set: fn ($value) => strtolower($value),

        );
    }

    //Accesor y Mutador para el atributo tipo_doc
    // protected function tipo_doc(): Attribute
    // {
    //     return new Attribute(

    //         // Accesor
    //         get: fn ($value) => ucwords($value),

    //         // mutador
    //         set: fn ($value) => strtolower($value),

    //     );
    // }
}
