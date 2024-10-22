<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function edit()
    {
        $usuario = auth()->user();
        return view('usuario.edit', compact('usuario'));
    }

    public function update(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|alpha_dash|unique:users,username,' . $usuario->id,
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => [
                'nullable',                 
                'string',                  
                'min:8',                 
                'regex:/[a-z]/',           
                'regex:/[A-Z]/',            
                'regex:/[0-9]/',
                'regex:/[\W_]/',   
                'not_regex:/\s/',            
                'confirmed',                
            ],
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',            
            'password.regex' => 'La contraseña debe tener al menos una letra minúscula, una mayúscula, un número y un carácter especial. ',
            'password.not_regex' => 'La contraseña no debe contener espacios en blanco.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $data = array_filter( $request->only(['name', 'username', 'email']));

        if ($request->hasFile('imagen')) {
            if ($usuario->imagen) {
                Storage::disk('public')->delete($usuario->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('images/usuarios', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $usuario->update($data);

        return redirect()->route('usuario.show')->with('success', 'Usuario actualizado correctamente.');
    }

    public function show()
    {
        $usuario = auth()->user();

        if (auth()->user()->id !== $usuario->id && auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para ver esta información.');
        }

        return view('usuario.show', compact('usuario'));
    }

    
}
