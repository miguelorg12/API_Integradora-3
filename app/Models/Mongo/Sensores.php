<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class Sensores extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sensores';
    protected $fillable = ['nombre', 'unidad', 'folio'];

    public function sensoresIncubadora()
    {
        return $this->hasMany(Sensores_Incubadoras::class);
    }
}
