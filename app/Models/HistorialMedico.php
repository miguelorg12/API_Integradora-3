<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialMedico extends Model
{
    use HasFactory;
    protected $table = 'historial_medicos';
    protected $fillable = ['id_bebe', 'diagnostico', 'medicamentos'];

    public function bebe()
    {
        return $this->belongsTo(Bebes::class);
    }
}
