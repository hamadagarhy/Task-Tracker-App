<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required|string',
            'email' => 'required|string|email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'please enter your new password.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
