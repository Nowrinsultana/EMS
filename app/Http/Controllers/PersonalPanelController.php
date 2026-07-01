<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadDocumentRequest;
use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PersonalPanelController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user()->load(['department', 'documents']);

        return view('panel.index', compact('user'));
    }

    public function upload(UploadDocumentRequest $request): RedirectResponse
    {
        $file = $request->file('document');
        $name = $request->input('name', $file->getClientOriginalName());

        $path = $file->store('documents/' . $request->user()->id, 'public');

        Document::create([
            'user_id' => $request->user()->id,
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        $user = $request->user();
        $admins = User::where('department_id', $user->department_id)
            ->where(fn ($q) => $q->where('isadmin', true)->orWhere('superuser', true))
            ->where('id', '!=', $user->id)
            ->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'document',
                'message' => "{$user->name} uploaded a document: {$name}.",
                'link' => route('documents.index', ['dptid' => $user->department_id]),
            ]);
        }

        return redirect()->route('panel.index', ['dptid' => $request->route('dptid')])
            ->with('status', 'Document uploaded successfully.');
    }

    public function destroy(Request $request, Document $document): RedirectResponse
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        Storage::disk('public')->delete($document->path);
        $document->delete();

        return redirect()->route('panel.index', ['dptid' => $request->route('dptid')])
            ->with('status', 'Document deleted successfully.');
    }

    public function download(Request $request, Document $document)
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        return Storage::disk('public')->download($document->path, $document->original_name);
    }
}
