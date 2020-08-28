<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $fillable = [
        'id',
        'nome',
        'login',
        'senha',
        'permissao',
        'dt_criacao'
    ];

    protected $table = 'usuarios';

    public function clientes()
    {
        return $this->hasMany(Clientes::class, 'usuario_id');
    }
}
