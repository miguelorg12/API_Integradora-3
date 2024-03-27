<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class Values extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'values';

    protected $fillable = [
        'sensor_value',
        'sensor_id',
        'incubadora_id',
        'bebe_id',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensores::class, 'sensor_id');
    }

    public function incubadora()
    {
        return $this->belongsTo(Incubadora::class, 'incubadora_id');
    }

    public function bebe()
    {
        return $this->belongsTo(Bebes::class, 'bebe_id');
    }
}