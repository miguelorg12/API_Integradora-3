<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $table = 'hospitals';
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
