<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string','confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'please enter your name',
            'email.required' => 'please enter your email',
            'email.email' => 'please enter valid email',
            'email.unique' => 'email already exists',
            'password.required' => 'please enter your password',
            'password.confirmed' => 'passwords do not match',
        ];
    }
}
