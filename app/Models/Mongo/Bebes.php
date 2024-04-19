<?php

namespace App\Models\Mongo;


use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\HistorialMedico;
use App\Models\ContactoFamiliar;

class Bebes extends Model
{

    public $timestamps = false;
    protected $connection = 'mongodb';
    protected $collection = 'bebes';
    protected $fillable = ['id_incubadora', 'id_estado', 'nombre', 'apellido', 'peso', 'talla', 'fecha_nacimiento', 'sexo'];

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
