<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'staff_id' => ['nullable', 'string', 'max:50', 'unique:users,staff_id,' . $this->user()->id],
            'leave_dates' => ['nullable', 'array'],
            'leave_dates.*' => ['date'],
        ];
    }
}
