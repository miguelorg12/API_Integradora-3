<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoDelBebe extends Model
{
    use HasFactory;
    protected $table = 'estado_del_bebes';
    protected $fillable = ['estado'];

    public function bebes()
    {
        return $this->hasMany(Bebes::class);
    }
}
