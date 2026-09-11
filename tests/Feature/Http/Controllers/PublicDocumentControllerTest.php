<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Document;
use App\Models\SignedFile;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicDocumentControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_upload_multiple_signed_files_for_one_document(): void
    {
        Storage::fake('private');
        $document = Document::factory()->pending()->create();
        $firstFile = UploadedFile::fake()->create(
            'signed-first.pdf',
            100,
            'application/pdf'
        );
        $secondFile = UploadedFile::fake()->image('signed-second.jpg');

        $response = $this->post(
            route('public.document.sign', $document->access_token),
            [
                'signature_name' => 'سارة أحمد',
                'signed_files' => [$firstFile, $secondFile],
                'agree' => '1',
            ]
        );

        $response
            ->assertOk()
            ->assertViewIs('public.completed')
            ->assertSee('تم استلام 2 من الملفات');

        $document->refresh();
        $this->assertSame('completed', $document->status);
        $this->assertSame(2, $document->signedFiles()->count());

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('private');

        $document->signedFiles->each(function (SignedFile $signedFile) use ($disk): void {
            $disk->assertExists($signedFile->path);
        });
    }

    public function test_guest_can_add_another_submission_to_completed_document(): void
    {
        Storage::fake('private');
        $document = Document::factory()->completed()->create();

        $this->post(
            route('public.document.sign', $document->access_token),
            [
                'signature_name' => 'نورة سالم',
                'signed_files' => [
                    UploadedFile::fake()->create('additional.pdf', 100, 'application/pdf'),
                ],
                'agree' => '1',
            ]
        )->assertOk();

        $this->assertSame(1, $document->signedFiles()->count());
    }

    public function test_signed_file_upload_rejects_more_than_ten_files(): void
    {
        Storage::fake('private');
        $document = Document::factory()->create();
        $files = [];

        foreach (range(1, 11) as $number) {
            $files[] = UploadedFile::fake()->create(
                'signed-'.$number.'.pdf',
                100,
                'application/pdf'
            );
        }

        $this->from(route('public.document', $document->access_token))
            ->post(
                route('public.document.sign', $document->access_token),
                [
                    'signature_name' => 'سارة أحمد',
                    'signed_files' => $files,
                    'agree' => '1',
                ]
            )
            ->assertRedirect(route('public.document', $document->access_token))
            ->assertSessionHasErrors('signed_files');

        $this->assertSame(0, $document->signedFiles()->count());
    }

    public function test_signed_file_upload_rejects_unsupported_file_types(): void
    {
        Storage::fake('private');
        $document = Document::factory()->create();

        $this->from(route('public.document', $document->access_token))
            ->post(
                route('public.document.sign', $document->access_token),
                [
                    'signature_name' => 'سارة أحمد',
                    'signed_files' => [
                        UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload'),
                    ],
                    'agree' => '1',
                ]
            )
            ->assertRedirect(route('public.document', $document->access_token))
            ->assertSessionHasErrors('signed_files.0');

        $this->assertSame(0, $document->signedFiles()->count());
    }

    public function test_admin_can_see_all_signed_files_on_document_page(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->create();
        SignedFile::factory()->for($document)->create([
            'original_name' => 'first-signed.pdf',
        ]);
        SignedFile::factory()->for($document)->create([
            'original_name' => 'second-signed.pdf',
        ]);

        $this->actingAs($user)
            ->get(route('documents.show', $document))
            ->assertOk()
            ->assertSee('first-signed.pdf')
            ->assertSee('second-signed.pdf');
    }

    public function test_guest_cannot_download_a_signed_file(): void
    {
        $signedFile = SignedFile::factory()->create();

        $this->get(route('signed-files.download', $signedFile))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_download_a_signed_file(): void
    {
        Storage::fake('private');
        $user = User::factory()->create();
        $signedFile = SignedFile::factory()->create([
            'original_name' => 'signed-copy.pdf',
            'path' => 'signed-documents/signed-copy.pdf',
        ]);
        Storage::disk('private')->put($signedFile->path, 'PDF content');

        $this->actingAs($user)
            ->get(route('signed-files.download', $signedFile))
            ->assertOk()
            ->assertDownload('signed-copy.pdf');
    }
}
