<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animais extends Model
{
    protected $fillable = [
        'id',
        'nome_animal',
        'cliente_id',
        'dt_nasc',
        'observacao',
        'microchip',
        'tag',
        'sexo',
        'castrado',
        'cor',
        'caminho_foto'
    ];

    protected $table = 'animais';
}
