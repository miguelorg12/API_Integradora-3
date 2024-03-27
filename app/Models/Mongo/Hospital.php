<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class Hospital extends Model
{
    protected $conection = 'mongodb';
    protected $collection = 'hospitals';
    protected $fillable = ['name', 'address', 'phone'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function incubadoras()
    {
        return $this->hasMany(Incubadora::class);
    }
}
