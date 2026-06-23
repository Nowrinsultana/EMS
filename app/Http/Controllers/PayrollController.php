<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBasicSalaryRequest;
use App\Http\Requests\StorePayrollAdjustmentRequest;
use App\Models\BasicSalary;
use App\Models\Payroll;
use App\Models\PayrollAdjustment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(Request $request): View
    {
        $dptid = $request->route('dptid'); $month = $request->string('month')->value() ?: now()->format('Y-m');
        $employees = User::where('department_id', $dptid)->where('superuser', false)->orderBy('name')->get();
        $payrolls = Payroll::with('employee')->where('department_id', $dptid)->whereDate('payroll_month', Carbon::createFromFormat('Y-m', $month)->startOfMonth())->get()->keyBy('user_id');
        return view('payroll.index', compact('dptid', 'month', 'employees', 'payrolls'));
    }

    public function storeSalary(StoreBasicSalaryRequest $request): RedirectResponse
    {
        $dptid = (int) $request->route('dptid'); $data = $request->validated();
        abort_unless(User::whereKey($data['user_id'])->where('department_id', $dptid)->exists(), 404);
        BasicSalary::updateOrCreate(['user_id' => $data['user_id'], 'effective_from' => $data['effective_from']], $data + ['department_id' => $dptid]);
        return back()->with('status', 'Basic salary saved.');
    }

    public function storeAdjustment(StorePayrollAdjustmentRequest $request): RedirectResponse
    {
        $dptid = (int) $request->route('dptid'); $data = $request->validated();
        abort_unless(User::whereKey($data['user_id'])->where('department_id', $dptid)->exists(), 404);
        $data['payroll_month'] = Carbon::createFromFormat('Y-m', $data['payroll_month'])->startOfMonth();
        PayrollAdjustment::create($data + ['department_id' => $dptid]);
        return back()->with('status', ucfirst($data['type']).' added.');
    }

    public function calculate(Request $request): RedirectResponse
    {
        $data = $request->validate(['month' => ['required','date_format:Y-m']]); $dptid = (int) $request->route('dptid'); $month = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth();
        DB::transaction(function () use ($dptid, $month) {
            User::where('department_id', $dptid)->where('superuser', false)->where('status', true)->each(function (User $employee) use ($dptid, $month) {
                $basic = BasicSalary::where('user_id', $employee->id)->whereDate('effective_from', '<=', $month)->latest('effective_from')->value('amount') ?? 0;
                $adjustments = PayrollAdjustment::where('user_id', $employee->id)->whereDate('payroll_month', $month)->get();
                $bonus = $adjustments->where('type', 'bonus')->sum('amount'); $deduction = $adjustments->where('type', 'deduction')->sum('amount');
                Payroll::updateOrCreate(['user_id' => $employee->id, 'payroll_month' => $month], ['department_id' => $dptid, 'basic_salary' => $basic, 'total_bonus' => $bonus, 'total_deduction' => $deduction, 'net_salary' => $basic + $bonus - $deduction, 'generated_at' => now()]);
            });
        });
        return redirect()->route('payroll.index', ['dptid' => $dptid, 'month' => $data['month']])->with('status', 'Monthly payroll calculated.');
    }

    public function slip(Request $request, $dptid, Payroll $payroll): Response
    {
        abort_if((int) $payroll->department_id !== (int) $dptid, 404); $payroll->load('employee');
        $lines = ['EMS PAY SLIP', 'Month: '.$payroll->payroll_month->format('F Y'), 'Employee: '.$payroll->employee->name, 'Staff ID: '.($payroll->employee->staff_id ?: 'N/A'), '', 'Basic Salary: '.number_format((float) $payroll->basic_salary, 2), 'Bonus: '.number_format((float) $payroll->total_bonus, 2), 'Deductions: '.number_format((float) $payroll->total_deduction, 2), 'NET PAY: '.number_format((float) $payroll->net_salary, 2), '', 'Generated: '.$payroll->generated_at->format('d M Y H:i')];
        $content = "BT /F1 18 Tf 50 760 Td (".$this->pdfText(array_shift($lines)).") Tj /F1 12 Tf "; foreach ($lines as $line) { $content .= "0 -28 Td (".$this->pdfText($line).") Tj "; } $content .= 'ET';
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            "<< /Length ".strlen($content)." >>\nstream\n$content\nendstream",
        ];
        $pdf = "%PDF-1.4\n"; $offsets = [0];
        foreach ($objects as $number => $object) { $offsets[] = strlen($pdf); $pdf .= ($number + 1)." 0 obj\n$object\nendobj\n"; }
        $xref = strlen($pdf); $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) { $pdf .= sprintf('%010d 00000 n ', $offset)."\n"; }
        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
        return response($pdf, 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="payslip-'.$payroll->id.'.pdf"']);
    }
    private function pdfText(string $text): string
    {
        $encoded = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text) ?: $text;
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $encoded);
    }
}
