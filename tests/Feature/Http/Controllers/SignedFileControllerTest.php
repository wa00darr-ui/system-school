<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Document;
use App\Models\SignedFile;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SignedFileControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_unauthenticated_preview_request_redirects_to_login(): void
    {
        $signedFile = SignedFile::factory()->create();

        $this->get(route('signed-files.preview', $signedFile))
            ->assertRedirect(route('login'));
    }

    public function test_preview_streams_the_file_inline(): void
    {
        Storage::fake('private');

        $user = User::factory()->create();
        $signedFile = $this->createStoredFile('report.pdf', 'application/pdf');

        $response = $this->actingAs($user)
            ->get(route('signed-files.preview', $signedFile))
            ->assertOk();

        $this->assertSame(
            'application/pdf',
            $response->headers->get('Content-Type')
        );

        $this->assertStringStartsWith(
            'inline;',
            (string) $response->headers->get('Content-Disposition')
        );
    }

    public function test_preview_is_not_available_for_unsupported_file_types(): void
    {
        Storage::fake('private');

        $user = User::factory()->create();
        $signedFile = $this->createStoredFile('page.html', 'text/html');

        $this->actingAs($user)
            ->get(route('signed-files.preview', $signedFile))
            ->assertNotFound();
    }

    public function test_preview_returns_not_found_when_the_file_is_missing(): void
    {
        Storage::fake('private');

        $user = User::factory()->create();
        $signedFile = SignedFile::factory()->create([
            'path' => 'signed-documents/missing.pdf',
            'mime_type' => 'application/pdf',
        ]);

        $this->actingAs($user)
            ->get(route('signed-files.preview', $signedFile))
            ->assertNotFound();
    }

    public function test_download_streams_the_file_as_an_attachment(): void
    {
        Storage::fake('private');

        $user = User::factory()->create();
        $signedFile = $this->createStoredFile('report.pdf', 'application/pdf');

        $response = $this->actingAs($user)
            ->get(route('signed-files.download', $signedFile))
            ->assertOk();

        $this->assertStringStartsWith(
            'attachment;',
            (string) $response->headers->get('Content-Disposition')
        );
    }

    public function test_document_page_shows_a_preview_button_for_previewable_files(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->create();
        $signedFile = SignedFile::factory()->for($document)->create([
            'mime_type' => 'application/pdf',
        ]);

        $this->actingAs($user)
            ->get(route('documents.show', $document))
            ->assertOk()
            ->assertSee(route('signed-files.preview', $signedFile))
            ->assertSee('عرض');
    }

    private function createStoredFile(
        string $name,
        string $mimeType
    ): SignedFile {
        $path = UploadedFile::fake()
            ->create($name, 10, $mimeType)
            ->store('signed-documents', 'private');

        return SignedFile::factory()->create([
            'original_name' => $name,
            'path' => $path,
            'mime_type' => $mimeType,
        ]);
    }
}
