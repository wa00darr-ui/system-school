<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicDocumentController extends Controller
{
    public function show(string $token)
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
    ) {
        $document = Document::where(
            'access_token',
            $token
        )->firstOrFail();

        if ($document->status !== 'pending') {
            return back()->withErrors([
                'document' => 'هذا المستند تمت معالجته مسبقًا.'
            ]);
        }

        $validated = $request->validate([
            'signature_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'signed_file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'agree' => [
                'accepted',
            ],
        ]);

        $signedPath = $request
            ->file('signed_file')
            ->store('signed-documents');

        $document->update([
            'signed_file' => $signedPath,

            'status' => 'completed',

            'signed_at' => now(),

            'completed_at' => now(),

            'ip_address' => $request->ip(),
        ]);

        return view(
            'public.completed',
            compact('document')
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
