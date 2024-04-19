<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incubadora extends Model
{
    use HasFactory;
    protected $table = 'incubadoras';
    protected $fillable = ['id_hospital', 'is_active', 'is_occupied', 'optimo', 'folio'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function bebe()
    {
        return $this->hasMany(Bebes::class);
    }

    public function sensores()
    {
        return $this->belongsToMany(Sensores::class, 'sensores__incubadoras', 'id_incubadora', 'id_sensor');
    }

    public function estadoIncubadora()
    {
        return $this->belongsTo(EstadoIncubadora::class);
    }
}
