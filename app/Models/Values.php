<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Values extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'values';
}
