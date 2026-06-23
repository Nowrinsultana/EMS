<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreJobVacancyRequest extends FormRequest { public function authorize(): bool { return true; } public function rules(): array { return ['title' => ['required','string','max:255'], 'description' => ['required','string'], 'location' => ['nullable','string','max:255'], 'employment_type' => ['required','string','max:100'], 'closing_date' => ['nullable','date'], 'status' => ['required','in:open,closed']]; } }
