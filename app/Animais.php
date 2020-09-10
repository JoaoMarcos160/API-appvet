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
        'caminho_foto',
        'created_at',
        'updated_at',
    ];

    protected $table = 'animais';

    public function clientes()
    {
        return $this->belongsTo(Clientes::class, 'cliente_id');
    }

    public function consultas()
    {
        return $this->hasMany(Consultas::class, 'animal_id');
    }
}
