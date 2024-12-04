<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reserva extends Model
{
    use HasFactory;
    public static $horaCierre = '20:00:00';

    protected $fillable = [
        'nombre_cliente', 
        'telefono_cliente',
        'servicio_id',
        'categoria_id',
        'fecha_reservacion', 
        'hora_reservacion', 
        'estado', 
        'duracion'
    
    ];
    public function getHoraFinAttribute()
    {
        $horaInicio = Carbon::createFromFormat('H:i:s', $this->hora_reservacion);
        $duracionTotal = $this->servicios->sum('duracion'); // Suma las duraciones de los servicios asociados
        return $horaInicio->addMinutes($duracionTotal)->format('H:i:s');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class);
    }
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class);
    }

}



