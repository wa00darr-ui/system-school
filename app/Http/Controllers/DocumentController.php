<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'in:teachers,administrators,parents'],
            'status' => ['nullable', 'in:pending,signed,completed,cancelled'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $documents = Document::query()
            ->filtered($filters)
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('documents.index', [
            'documents' => $documents,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'category' => [
                'required',
                'in:teachers,administrators,parents',
            ],

            'recipient_name' => [
                'required',
                'string',
                'max:255',
            ],

            'recipient_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'recipient_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'original_file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240',
            ],
        ]);

        $path = $request
            ->file('original_file')
            ->store('school-documents', 'public');

        $document = Document::create([
            'title' => $validated['title'],

            'description' => $validated['description'] ?? null,

            'category' => $validated['category'],

            'recipient_name' => $validated['recipient_name'],

            'recipient_phone' => $validated['recipient_phone'] ?? null,

            'recipient_email' => $validated['recipient_email'] ?? null,

            'original_file' => $path,

            'status' => 'pending',
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'تم إنشاء المستند والرابط الخاص بنجاح.');
    }

    public function show(Document $document)
    {
        $document->load([
            'signedFiles' => fn ($query) => $query->latest(),
        ]);

        return view('documents.show', compact('document'));
    }

    public function destroy(Document $document)
    {
        if ($document->original_file) {
            Storage::disk('public')->delete($document->original_file);
        }

        if ($document->signed_file) {
            Storage::delete($document->signed_file);
        }

        $document->signedFiles()->each(function ($signedFile): void {
            Storage::disk('private')->delete($signedFile->path);
        });

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'تم حذف المستند.');
    }
}
