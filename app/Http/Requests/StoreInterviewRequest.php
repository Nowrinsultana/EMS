<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreInterviewRequest extends FormRequest { public function authorize(): bool { return true; } public function rules(): array { return ['scheduled_at' => ['required','date'], 'interview_type' => ['required','string','max:100'], 'location' => ['nullable','string','max:255'], 'notes' => ['nullable','string']]; } }
