<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensores_Incubadoras extends Model
{
    use HasFactory;
    protected $table = 'sensores_incubadoras';

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
