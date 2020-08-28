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
        'cidade',
        'estado',
        'cep',
        'dt_nasc',
        'observacao',
        'email',
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
