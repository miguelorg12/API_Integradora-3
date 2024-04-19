<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\User;

class Hospital extends Model
{

    public $timestamps = false;
    protected $connection = 'mongodb';
    protected $collection = 'hospitals';
    protected $fillable = ['nombre', 'direccion', 'telefono'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function incubadoras()
    {
        return $this->hasMany(Incubadora::class);
    }
}
