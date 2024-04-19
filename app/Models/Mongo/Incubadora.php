<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class Incubadora extends Model
{


    public $timestamps = false;
    protected $connection = 'mongodb';
    protected $collection = 'incubadoras';
    protected $fillable = ['id_hospital', 'is_active', 'is_occupied', 'optimo', 'folio'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function bebe()
    {
        return $this->hasMany(Bebes::class);
    }

    public function sensoresIncubadora()
    {
        return $this->hasMany(Sensores_Incubadoras::class);
    }
}
