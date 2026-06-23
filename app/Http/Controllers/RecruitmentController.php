<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateApplicationRequest;
use App\Http\Requests\StoreInterviewRequest;
use App\Http\Requests\StoreJobVacancyRequest;
use App\Models\CandidateApplication;
use App\Models\Interview;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecruitmentController extends Controller
{
    public function index(Request $request): View { $dptid = $request->route('dptid'); $vacancies = JobVacancy::where('department_id', $dptid)->withCount('applications')->latest()->get(); return view('recruitment.index', compact('dptid', 'vacancies')); }
    public function create(Request $request): View { return view('recruitment.vacancy-form', ['dptid' => $request->route('dptid'), 'vacancy' => new JobVacancy]); }
    public function store(StoreJobVacancyRequest $request): RedirectResponse { JobVacancy::create($request->validated() + ['department_id' => $request->route('dptid')]); return redirect()->route('recruitment.index', ['dptid' => $request->route('dptid')])->with('status', 'Job vacancy posted.'); }
    public function edit(Request $request, $dptid, JobVacancy $vacancy): View { $this->belongs($vacancy, $dptid); return view('recruitment.vacancy-form', compact('dptid', 'vacancy')); }
    public function update(StoreJobVacancyRequest $request, $dptid, JobVacancy $vacancy): RedirectResponse { $this->belongs($vacancy, $dptid); $vacancy->update($request->validated()); return redirect()->route('recruitment.index', compact('dptid'))->with('status', 'Vacancy updated.'); }
    public function applications(Request $request, $dptid, JobVacancy $vacancy): View { $this->belongs($vacancy, $dptid); $applications = $vacancy->applications()->with('interviews')->latest()->get(); return view('recruitment.applications', compact('dptid','vacancy','applications')); }
    public function updateApplication(Request $request, $dptid, CandidateApplication $application): RedirectResponse { $this->belongs($application->vacancy, $dptid); $data = $request->validate(['status' => ['required','in:new,reviewing,interview,selected,rejected']]); $application->update($data); return back()->with('status', 'Candidate status updated.'); }
    public function storeInterview(StoreInterviewRequest $request, $dptid, CandidateApplication $application): RedirectResponse { $this->belongs($application->vacancy, $dptid); Interview::create($request->validated() + ['candidate_application_id' => $application->id]); $application->update(['status' => 'interview']); return back()->with('status', 'Interview scheduled.'); }
    public function applicationForm(JobVacancy $vacancy): View { abort_if($vacancy->status !== 'open' || ($vacancy->closing_date && $vacancy->closing_date->isPast()), 404); return view('recruitment.apply', compact('vacancy')); }
    public function apply(StoreCandidateApplicationRequest $request, JobVacancy $vacancy): RedirectResponse { abort_if($vacancy->status !== 'open' || ($vacancy->closing_date && $vacancy->closing_date->isPast()), 404); CandidateApplication::create($request->validated() + ['job_vacancy_id' => $vacancy->id]); return back()->with('status', 'Your application has been submitted successfully.'); }
    private function belongs(JobVacancy $vacancy, $dptid): void { abort_if((int) $vacancy->department_id !== (int) $dptid, 404); }
}
