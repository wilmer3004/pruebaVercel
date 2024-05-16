<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionHora extends Model
{

    protected $table = 'conditions_hours';

    use HasFactory;

    protected $fillable = [
        'contract_id',
        'condition_id',
        'percentage',
        'state'
    ];

    // Relación con Contrato
    public function contrato()
    {
        return $this->belongsTo('App\Models\Contrato', 'contract_id', 'id');
    }

    // Relación con Condiciones
    public function condicion()
    {
        return $this->belongsTo('App\Models\Condicion', 'condition_id', 'id');
    }
}
