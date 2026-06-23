<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StorePayrollAdjustmentRequest extends FormRequest { public function authorize(): bool { return true; } public function rules(): array { return ['user_id' => ['required','integer','exists:users,id'], 'type' => ['required','in:bonus,deduction'], 'amount' => ['required','numeric','min:0.01'], 'description' => ['required','string','max:255'], 'payroll_month' => ['required','date_format:Y-m']]; } }
