<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash; // Importar Hash

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'imagen',
    ];

    /**
     * Los atributos que deben permanecer ocultos.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que deben ser casteados.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Encripta la contraseña automáticamente.
     */
    public function setPasswordAttribute($password)
    {
        if ($password) { 
            $this->attributes['password'] = Hash::make($password);
        }
    }

    public function adminlte_image()
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : asset('imagenes/usuario.jpg');
    }

    public function adminlte_desc()
    {
        return auth()->user()->username;
    }
}
