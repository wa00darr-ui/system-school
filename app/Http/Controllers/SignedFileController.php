<?php

namespace App\Http\Controllers;

use App\Models\SignedFile;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SignedFileController extends Controller
{
    public function __invoke(SignedFile $signedFile): StreamedResponse
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('private');

        abort_unless(
            $disk->exists($signedFile->path),
            404
        );

        return $disk->download(
            $signedFile->path,
            $signedFile->original_name
        );
    }
}
