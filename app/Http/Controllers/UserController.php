<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


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
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $data = $request->only(['name', 'username']);

    if ($request->hasFile('imagen')) {
        if ($usuario->imagen) {
            Storage::disk('public')->delete($usuario->imagen);
        }
        $data['imagen'] = $request->file('imagen')->store('images/usuarios', 'public');
    }

    if (!$usuario->update($data)) {
        return back()->withErrors(['error' => 'Error al actualizar el usuario. Intente de nuevo.']);
    }

    auth()->setUser($usuario);

    return redirect()->route('usuario.show')->with('success', 'Usuario actualizado correctamente.');
}
public function editPassword()
{
    $usuario = auth()->user();
    return view('usuario.editPassword', compact('usuario'));
}

public function updatePassword(Request $request)
{
    $usuario = auth()->user();

    Log::debug('Hash almacenado: ' . $usuario->password);

    $request->validate([
      'current_password' => [
            'required',
            function ($attribute, $value, $fail) use ($usuario) {
                if (!Hash::check($value, $usuario->password)) {
                    $fail('La contraseña actual es incorrecta.');
                }
            },
        ],
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
    ], [
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.regex' => 'La contraseña debe tener al menos una letra minúscula, una mayúscula, un número y un carácter especial.',
        'password.not_regex' => 'La contraseña no debe contener espacios en blanco.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        'current_password.required' => 'Debe ingresar su contraseña actual para realizar cambios.',
    ]);

    $usuario->password =$request->input('password');
    $usuario->save();
    Log::debug('Verificación directa del hash: ' . (Hash::check($request->input('password'), $usuario->password) ? 'Correcto' : 'Incorrecto'));
    auth()->login($usuario);
    Log::debug('Nuevo hash generado: ' .":". $usuario->password); 
    
    Log::debug('Usuario re-autenticado después de cambiar la contraseña.');

    return redirect()->route('usuario.show')->with('success', 'Contraseña actualizada correctamente.');
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
