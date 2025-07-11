<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComponentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titleComponente' => 'required|string|max:255|unique:components,titleComponente',
            // |unique:components,titleComponente
            'description' => 'required|string|max:2000',
            'contentComponente' => 'nullable|string',
            'rutaImagenComponente' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
