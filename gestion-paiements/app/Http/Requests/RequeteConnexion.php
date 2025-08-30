<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequeteConnexion extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'mot_de_passe' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'L’email est requis.',
            'mot_de_passe.required' => 'Le mot de passe est requis.',
        ];
    }
}
