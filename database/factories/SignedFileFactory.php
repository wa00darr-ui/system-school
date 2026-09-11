<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\SignedFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SignedFile>
 */
class SignedFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'original_name' => fake()->word().'.pdf',
            'path' => 'signed-documents/example.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1024,
            'signature_name' => fake()->name(),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
