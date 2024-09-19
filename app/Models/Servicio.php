<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'categoria_id', 'imagen','disponibilidad'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function images()
    {
        return $this->hasMany(ServicioImage::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
