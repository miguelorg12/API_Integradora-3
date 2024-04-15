<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bebes extends Model
{
    use HasFactory;
    protected $table = 'bebes';
    protected $fillable = ['id_incubadora', 'id_estado', 'nombre', 'apellido', 'sexo', 'fecha_nacimiento', 'edad', 'peso'];

    public function incubadora()
    {
        return $this->belongsTo(Incubadora::class);
    }

    public function historial()
    {
        return $this->hasOne(HistorialMedico::class, 'id_bebe');
    }

    public function contactoFamiliar()
    {
        return $this->hasMany(ContactoFamiliar::class);
    }

    public function estado()
    {
        return $this->belongsTo(EstadoDelBebe::class);
    }
}
