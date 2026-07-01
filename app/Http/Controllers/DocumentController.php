<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $dptid = $request->route('dptid');

        $documents = Document::whereHas('user', fn ($q) => $q->where('department_id', $dptid))
            ->with('user')
            ->latest()
            ->get();

        $employees = User::where('department_id', $dptid)
            ->where('superuser', false)
            ->orderBy('name')
            ->get();

        return view('documents.index', compact('documents', 'employees', 'dptid'));
    }

    public function upload(Request $request): RedirectResponse
    {
        $dptid = $request->route('dptid');

        $data = $request->validate([
            'employee_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
        ]);

        $employee = User::findOrFail($data['employee_id']);
        abort_if((int) $employee->department_id !== (int) $dptid, 404);

        $path = $request->file('file')->store('documents/' . $employee->id, 'public');

        Document::create([
            'user_id' => $employee->id,
            'name' => $data['name'],
            'original_name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $request->file('file')->getMimeType(),
            'size' => $request->file('file')->getSize(),
        ]);

        Notification::create([
            'user_id' => $employee->id,
            'type' => 'document',
            'message' => $request->user()->name . ' uploaded a document: ' . $data['name'] . '.',
        ]);

        return redirect()->route('documents.index', ['dptid' => $dptid])
            ->with('status', 'Document uploaded successfully.');
    }

    public function download(Request $request, $dptid, Document $document)
    {
        $dptid = (int) $dptid;

        if ((int) $document->user->department_id !== $dptid) {
            abort(404);
        }

        return Storage::disk('public')->download($document->path, $document->original_name);
    }

    public function destroy(Request $request, $dptid, Document $document): RedirectResponse
    {
        $dptid = (int) $dptid;

        if ((int) $document->user->department_id !== $dptid) {
            abort(404);
        }

        Storage::disk('public')->delete($document->path);
        $document->delete();

        return redirect()->route('documents.index', ['dptid' => $dptid])
            ->with('status', 'Document deleted successfully.');
    }
}
