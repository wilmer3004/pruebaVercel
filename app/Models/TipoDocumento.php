<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{

    protected $table = 'documents_type';

    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
        'state'
    ];
}
