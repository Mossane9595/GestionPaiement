<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequeteInscription extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autoriser tout le monde
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'email.required' => 'L’adresse email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
