<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoIncubadora extends Model
{
    use HasFactory;
    protected $table = 'estado_incubadoras';
    protected $fillable = ['estado'];

    public function incubadoras()
    {
        return $this->hasMany(Incubadora::class);
    }
}
