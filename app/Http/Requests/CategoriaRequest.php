<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
{
    $categoriaId = $this->route('categoria') ? $this->route('categoria')->id : null;

    return [
        'nombre' => [
            'required',
            'string',
            'max:50',
            'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/',
            Rule::unique('categorias', 'nombre')->ignore($categoriaId),
        ],
        'descripcion' => 'required|string|max:255',
        'estado' => 'required|boolean',
        'imagen' => [
            $this->isMethod('post') ? 'required' : 'nullable', 
            'image',
            'max:2048',
        ],
    ];
}

}