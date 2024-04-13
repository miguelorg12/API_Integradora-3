<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensores extends Model
{
    use HasFactory;
    protected $table = 'sensores';
    protected $fillable = ['nombre', 'unidad', 'folio'];

    public function incubadoras()
    {
        return $this->belongsToMany(Incubadora::class, 'sensores__incubadoras', 'id_sensor', 'id_incubadora');
    }
}
