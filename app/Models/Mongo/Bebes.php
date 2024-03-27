<?php

namespace App\Models\Mongo;


use Jenssegers\Mongodb\Eloquent\Model;

class Bebes extends Model
{
    protected $conection = 'mongodb';
    protected $collection = 'bebes';
    protected $fillable = ['id_incubadora', 'id_estado', 'nombre', 'apellido'];

    public function incubadora()
    {
        return $this->belongsTo(Incubadora::class);
    }

    public function historial()
    {
        return $this->hasOne(HistorialMedico::class);
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
