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
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'permissao' => '3',
    ];

    protected $table = 'usuarios';

    public function clientes()
    {
        return $this->hasMany(Clientes::class, 'usuario_id');
    }
}
