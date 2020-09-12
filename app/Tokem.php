<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tokem extends Model
{
    protected $fillable = [
        'id',
        'tokem',
        'usuario_id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'tokens';

    public function usuarios()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    } 
}
