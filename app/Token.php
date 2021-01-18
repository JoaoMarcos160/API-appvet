<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'id',
        'token',
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
