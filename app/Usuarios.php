<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $fillable = [
        'nome',
        'login',
        'senha',
        'permissao',
    ];

    protected $table = 'usuarios';

    public function clientes()
    {
        return $this->hasMany(Clientes::class, 'usuario_id');
    }
}
