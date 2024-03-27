<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class Sensores extends Model
{
    protected $conection = 'mongodb';
    protected $collection = 'sensores';
    protected $fillable = ['nombre', 'unidad'];

    public function sensoresIncubadora()
    {
        return $this->hasMany(Sensores_Incubadoras::class);
    }
}
