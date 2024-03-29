<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class Sensores_Incubadoras extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sensores_incubadoras';
    protected $fillable = ['id_incubadora', 'id_sensor'];

    public function incubadora()
    {
        return $this->belongsTo(Incubadora::class);
    }

    public function sensores()
    {
        return $this->hasMany(Sensores::class);
    }
}
