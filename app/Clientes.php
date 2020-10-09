<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $fillable = [
        'id',
        'usuario_id',
        'nome',
        'cpf',
        'telefone',
        'endereco',
        'bairro',
        'numero',
        'complemento',
        'cidade',
        'estado',
        'cep',
        'dt_nasc',
        'observacao',
        'email',
        'created_at',
        'updated_at',
    ];

    protected $table = 'clientes';

    public function usuarios()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }

    public function animais()
    {
        return $this->hasMany(Animais::class, 'cliente_id');
    }
}
