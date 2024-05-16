<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimestreAnio extends Model
{
    protected $table = 'year_quarters';

    use HasFactory;

    protected $fillable = [
        'year',
        'quarter',
        'start_date',
        'finish_date',
        'state'
    ];
}
