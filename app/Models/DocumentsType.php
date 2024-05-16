<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsType extends Model
{
    use HasFactory;

    protected $table = 'documents_type';
    use HasFactory;

    protected $fillable = [
        'name',
        'nicknames',
        'state'
    ];

    // Relación uno a muchos con Persona
    public function personas()
    {
        return $this->hasMany('App\Models\Persona', 'document_type_id', 'id');
    }


}
