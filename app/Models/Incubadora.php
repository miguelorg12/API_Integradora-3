<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incubadora extends Model
{
    use HasFactory;
    protected $table = 'incubadoras';
    protected $fillable = ['id_hospital', 'is_active', 'is_occupied', 'optimo'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function bebe()
    {
        return $this->hasMany(Bebes::class);
    }

    public function sensoresIncubadora()
    {
        return $this->hasMany(Sensores_Incubadoras::class);
    }
}
