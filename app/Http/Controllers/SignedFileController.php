<?php

namespace App\Http\Controllers;

use App\Models\SignedFile;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SignedFileController extends Controller
{
    public function download(SignedFile $signedFile): StreamedResponse
    {
        $disk = $this->disk();

        abort_unless(
            $disk->exists($signedFile->path),
            404
        );

        return $disk->download(
            $signedFile->path,
            $signedFile->original_name
        );
    }

    public function preview(SignedFile $signedFile): StreamedResponse
    {
        $disk = $this->disk();

        abort_unless(
            $signedFile->isPreviewable(),
            404
        );

        abort_unless(
            $disk->exists($signedFile->path),
            404
        );

        return $disk->response(
            $signedFile->path,
            $signedFile->original_name,
            [
                'Content-Type' => $signedFile->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline'
        );
    }

    private function disk(): FilesystemAdapter
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('private');

        return $disk;
    }
}
