<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use App\Models\Servicio;
class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','descripcion','estado','imagen'];

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}


