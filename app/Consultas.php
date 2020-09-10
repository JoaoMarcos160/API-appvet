<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consultas extends Model
{
    protected $fillable = [
        'id',
        'animal_id',
        'observacao',
        'doenca',
        'recomendacao',
        'valor_cobrado',
        'created_at',
        'updated_at',

    ];

    protected $table = 'consultas';

    public function animais()
    {
        return $this->belongsTo(Animais::class, 'animal_id');
    }
}
