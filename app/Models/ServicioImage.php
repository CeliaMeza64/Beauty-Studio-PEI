<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Servicio;

class ServicioImage extends Model
{
    use HasFactory;

    protected $fillable = ['servicio_id', 'path'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
