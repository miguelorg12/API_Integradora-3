<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Value extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'Values';
    protected $fillable = ['name', 'unit', 'value'];
}
