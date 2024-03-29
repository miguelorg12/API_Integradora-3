<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensores_Incubadoras extends Model
{
    use HasFactory;
    protected $table = 'sensores__incubadoras';

    protected $fillable = ['id_incubadora', 'id_sensor'];

    public function incubadora()
    {
        return $this->belongsToMany(Incubadora::class);
    }

    public function sensores()
    {
        return $this->belongsToMany(Sensores::class);
    }
}
