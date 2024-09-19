<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_cliente', 
        'servicio_id',
        'categoria_id',
        'fecha_reservacion', 
        'hora_reservacion', 
        'estado', 
        'telefono_cliente'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}



