<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'date_of_birth' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'staff_id' => ['nullable', 'string', 'max:50', 'unique:users,staff_id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'staff_id' => 'staff ID',
            'date_of_birth' => 'date of birth',
            'phone_number' => 'phone number',
            'passport_number' => 'passport number',
        ];
    }
}
