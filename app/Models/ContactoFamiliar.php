<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoFamiliar extends Model
{
    use HasFactory;

    protected $table = 'contacto_familiars';
    protected $fillable = ['nombre', 'apellido', 'telefono', 'email', 'id_bebe'];

    public function bebes()
    {
        return $this->belongsTo(Bebes::class);
    }
}
