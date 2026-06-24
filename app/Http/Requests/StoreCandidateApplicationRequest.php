<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreCandidateApplicationRequest extends FormRequest { public function authorize(): bool { return true; } public function rules(): array { return ['name' => ['required','string','max:255'], 'email' => ['required','email','max:255'], 'phone_number' => ['required','string','max:30'], 'cover_letter' => ['nullable','string'], 'resume' => ['nullable','file','mimes:pdf','max:5120']]; } }
