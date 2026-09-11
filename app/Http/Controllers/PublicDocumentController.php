<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PublicDocumentController extends Controller
{
    public function show(string $token): View
    {
        $document = Document::where(
            'access_token',
            $token
        )->firstOrFail();

        return view(
            'public.document',
            compact('document')
        );
    }

    public function sign(
        Request $request,
        string $token
    ): View {
        $document = Document::where(
            'access_token',
            $token
        )->firstOrFail();

        $validated = $request->validate([
            'signature_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'signed_files' => [
                'required',
                'array',
                'min:1',
                'max:10',
            ],

            'signed_files.*' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'extensions:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'agree' => [
                'accepted',
            ],
        ]);

        foreach ($request->file('signed_files') as $uploadedFile) {
            $path = $uploadedFile->store(
                'signed-documents/'.$document->id,
                'private'
            );

            $document->signedFiles()->create([
                'original_name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $uploadedFile->getMimeType() ?? 'application/octet-stream',
                'size' => $uploadedFile->getSize(),
                'signature_name' => $validated['signature_name'],
                'ip_address' => $request->ip(),
            ]);
        }

        $document->update([
            'status' => 'completed',
            'signed_at' => now(),
            'completed_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        return view(
            'public.completed',
            [
                'document' => $document,
                'signatureName' => $validated['signature_name'],
                'uploadedFilesCount' => count($validated['signed_files']),
            ]
        );
    }

    public function downloadOriginal(string $token)
    {
        $document = Document::where(
            'access_token',
            $token
        )->firstOrFail();

        abort_unless(
            Storage::exists($document->original_file),
            404
        );

        return Storage::download(
            $document->original_file
        );
    }
}
