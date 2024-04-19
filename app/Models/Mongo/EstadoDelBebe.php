<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class EstadoDelBebe extends Model
{

    public $timestamps = false;
    protected $connection = 'mongodb';
    protected $collection = 'estado_del_bebes';
    protected $fillable = ['estado'];

    public function bebes()
    {
        return $this->hasMany(Bebes::class);
    }
}
