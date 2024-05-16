<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    protected $table = "skills";

    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'description',
    ];

    //Relacion con componente
    public function componente(){
        return $this->belongsTo('App\Models\Componente','component_id','id');
    }

}
