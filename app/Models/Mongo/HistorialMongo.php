<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class HistorialMongo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'backup';
    protected $fillable = ['name', 'unit', 'value'];
}
